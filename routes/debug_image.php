<?php
use Illuminate\Support\Facades\Route;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Storage;

Route::get('/debug-media', function () {
    $media = Media::latest()->first();
    if (!$media) return 'No media found';
    
    echo "Media ID: " . $media->id . "<br>";
    echo "Filename: " . $media->name . "<br>";
    echo "Path: " . $media->path . "<br>";
    echo "Disk: " . $media->disk . "<br>";
    echo "Exists in Storage? " . (Storage::disk($media->disk)->exists($media->path) ? 'YES' : 'NO') . "<br>";
    echo "Curator URL: " . $media->url . "<br>"; // Uses getUrlAttribute
    
    // Try to simulate Glide route?
    // Route is usually /curator/media/{path}
    return 'Done';
});
