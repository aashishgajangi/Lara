<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageOptimizationService
{
    protected $manager;

    public function __construct()
    {
        // Intervention Image v2 setup
        $this->manager = new ImageManager(['driver' => 'gd']);
    }

    public function process(Model $model, string $attributeName, string $disk = 'public')
    {
        $settings = $this->getSettings();

        if (!$settings['enabled']) {
            return;
        }

        $imagePath = $model->{$attributeName};

        if (!$imagePath || !Storage::disk($disk)->exists($imagePath)) {
            return;
        }

        // Check if already optimized (simple check by extension)
        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
        $targetFormat = $settings['format'];

        if ($targetFormat !== 'original' && strtolower($extension) !== $targetFormat) {
            $this->convertAndReplace($model, $attributeName, $imagePath, $targetFormat, $settings['quality'], $disk);
        }
    }

    protected function convertAndReplace(Model $model, string $attributeName, string $imagePath, string $format, int $quality, string $disk)
    {
        $fileContent = Storage::disk($disk)->get($imagePath);
        
        // v2: ensure we use make()
        $image = $this->manager->make($fileContent);

        // v2: encode() handles formats
        $encoded = $image->encode($format, $quality);

        $newPath = preg_replace('/\.[^.]+$/', '.' . $format, $imagePath);

        // Save new file
        Storage::disk($disk)->put($newPath, (string) $encoded);

        // Delete old file if path is different
        if ($newPath !== $imagePath) {
            Storage::disk($disk)->delete($imagePath);
            
            // Update Model
            $model->{$attributeName} = $newPath;
            $model->saveQuietly(); // Avoid triggering infinite loops if called from observer
        }
    }

    protected function getSettings()
    {
        return [
            'enabled' => Setting::where('key', 'image_optimization_enabled')->value('value'),
            'format' => Setting::where('key', 'image_format')->value('value') ?? 'original',
            'quality' => (int) (Setting::where('key', 'image_quality')->value('value') ?? 80),
        ];
    }
}
