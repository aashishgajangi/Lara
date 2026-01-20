<?php

namespace App\Helpers;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaHelper
{
    /**
     * Resolve a media value (ID or Path) to a full URL.
     * Handles media IDs, paths, and URLs with graceful fallback for deleted media.
     *
     * @param mixed $value Media ID, path, or URL
     * @param string|null $default Fallback value if media not found
     * @return string|null Resolved URL or default
     */
    public static function resolveUrl($value, $default = null)
    {
        if (! $value) {
            return $default;
        }

        // If numeric, assume it's a Curator Media ID
        if (is_numeric($value)) {
            try {
                $media = Media::find($value);
                
                // Check if media exists and is not soft-deleted
                if ($media && !$media->trashed()) {
                    return $media->url;
                }
                
                // Media deleted or not found - log warning and return default
                \Illuminate\Support\Facades\Log::warning('Media not found or deleted', [
                    'media_id' => $value,
                    'default' => $default
                ]);
                
                return $default;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Media resolution failed', [
                    'media_id' => $value,
                    'error' => $e->getMessage()
                ]);
                return $default;
            }
        }

        // If already a URL, return as is (handling Legacy absolute URLs)
        if (filter_var($value, FILTER_VALIDATE_URL)) {
             return $value;
        }

        // Check if it already looks like a relative/absolute path to storage (prevents double wrapping)
        if (str_starts_with($value, '/storage') || str_starts_with($value, 'storage')) {
             return '/' . ltrim($value, '/');
        }

        // Assume it's a relative path in storage (e.g. 'logo/foo.png')
        return Storage::url($value);
    }
}
