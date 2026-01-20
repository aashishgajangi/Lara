<?php

namespace App\Observers;

use App\Models\Media;

class CuratorMediaObserver
{
    public function __construct(protected \App\Services\ImageOptimizationService $service) {}

    /**
     * Handle the Media "created" event.
     */
    public function created(Media $media): void
    {
        try {
            // Run optimization service
            // This will update the 'path' attribute if format changes (e.g. jpg -> webp)
            $this->service->process($media, 'path', $media->disk);
            
            // Refresh to get the potentially updated path
            $media->refresh();

            // Update metadata (size, type, ext) which might have changed
            if (\Illuminate\Support\Facades\Storage::disk($media->disk)->exists($media->path)) {
                $fullPath = \Illuminate\Support\Facades\Storage::disk($media->disk)->path($media->path);
                
                $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
                $type = mime_content_type($fullPath);
                $size = filesize($fullPath);
                $width = $media->width;
                $height = $media->height;
                
                // Try to get new dimensions if valuable
                if ($sizeInfo = @getimagesize($fullPath)) {
                    $width = $sizeInfo[0];
                    $height = $sizeInfo[1];
                }

                $media->updateQuietly([
                    'ext' => $ext,
                    'type' => $type,
                    'size' => $size,
                    'width' => $width,
                    'height' => $height,
                ]);
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the upload
            \Illuminate\Support\Facades\Log::error('Media optimization failed', [
                'media_id' => $media->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Media upload continues with original file
        }
    }
}
