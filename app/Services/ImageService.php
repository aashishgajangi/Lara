<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

class ImageService
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(['driver' => 'gd']);
    }

    /**
     * Process and store uploaded image with SEO-friendly naming and WebP conversion
     */
    public function processAndStore(UploadedFile $file, string $type = 'general', ?string $customName = null): string
    {
        // Create SEO-friendly filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $seoName = $customName ?? Str::slug($originalName);
        $timestamp = now()->timestamp;
        
        // Create organized folder structure: type/YYYY/MM/DD
        $folderPath = sprintf(
            '%s/%s/%s/%s',
            $type,
            now()->format('Y'),
            now()->format('m'),
            now()->format('d')
        );

        // Generate unique filename
        $filename = sprintf('%s-%s.webp', $seoName, $timestamp);
        $fullPath = $folderPath . '/' . $filename;

        // Load and optimize image (v2)
        $image = $this->imageManager->make($file->getRealPath());

        // Resize if too large (max 1920px width)
        if ($image->width() > 1920) {
            $image->resize(1920, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Convert to WebP with quality 85 (v2)
        $encodedImage = $image->encode('webp', 85);

        // Store the image
        Storage::disk('public')->put($fullPath, (string) $encodedImage);

        return $fullPath;
    }

    /**
     * Delete image from storage
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get full URL for image
     */
    public function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}
