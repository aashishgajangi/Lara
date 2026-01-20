<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncMediaCommand extends Command
{
    protected $signature = 'media:sync-curator';
    protected $description = 'Sync existing product images and site settings to Curator Media Library';

    public function handle()
    {
        $this->info('Starting full filesystem media sync...');
        
        // 1. Scan filesystem for ALL images
        $files = \Illuminate\Support\Facades\Storage::disk('public')->allFiles();
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'];
        
        $count = 0;
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            
            // Skip if not an image or is already in curator's cache/thumbnails (if any)
            if (!in_array($ext, $imageExtensions)) {
                continue;
            }
            
            // Skip hidden system files
            if (str_starts_with(basename($file), '.')) {
                continue;
            }

            $this->createMediaEntry($file, '', 'Filesystem Scan');
            $count++;
        }

        $this->info("Scanned $count files. Media sync complete!");
    }

    protected function createMediaEntry($path, $alt, $context)
    {
        // Check if already in Curator map (by path)
        if (\Awcodes\Curator\Models\Media::where('path', $path)->exists()) {
            return;
        }

        if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            $this->warn("File not found: $path");
            return;
        }

        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
        
        try {
            $type = mime_content_type($fullPath);
            $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
            $name = pathinfo($fullPath, PATHINFO_FILENAME);
            $size = filesize($fullPath);
            
            list($width, $height) = getimagesize($fullPath) ?: [null, null];

            \Awcodes\Curator\Models\Media::create([
                'disk' => 'public',
                'directory' => dirname($path),
                'name' => $name,
                'path' => $path,
                'width' => $width,
                'height' => $height,
                'size' => $size,
                'type' => $type,
                'ext' => $ext,
                'alt' => $alt,
                'title' => $name,
            ]);
            
            $this->info("Imported: $name ($context)");
        } catch (\Exception $e) {
            $this->error("Failed to import $path: " . $e->getMessage());
        }
    }
}
