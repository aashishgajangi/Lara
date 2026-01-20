<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupOrphanedMedia extends Command
{
    protected $signature = 'media:cleanup-orphaned';
    protected $description = 'Remove DB records pointing to missing files';

    public function handle()
    {
        $this->info('Starting orphaned media cleanup...');
        
        // 1. Cleanup Product Images
        $productImages = \App\Models\ProductImage::all();
        $deletedProducts = 0;
        
        foreach ($productImages as $image) {
            if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_path)) {
                $this->warn("Removing missing product image: {$image->image_path}");
                $image->delete();
                $deletedProducts++;
            }
        }
        $this->info("Removed $deletedProducts broken product images.");

        // 2. Cleanup Site Settings
        $settings = \App\Models\SiteSetting::where('key', 'like', '%_image')->get();
        $updatedSettings = 0;

        foreach ($settings as $setting) {
            if ($setting->value && !\Illuminate\Support\Facades\Storage::disk('public')->exists($setting->value)) {
                $this->warn("Clearing missing site setting: {$setting->key} ({$setting->value})");
                $setting->update(['value' => null]);
                $updatedSettings++;
            }
        }
        $this->info("Cleared $updatedSettings broken site settings.");
        
        $this->info('Cleanup complete!');
    }
}
