<?php

namespace App\Observers;

class ImageObserver
{
    protected $service;

    public function __construct(\App\Services\ImageOptimizationService $service)
    {
        $this->service = $service;
    }

    public function saved($model)
    {
        if ($model instanceof \App\Models\Category) {
            if ($model->isDirty('image') && $model->image) {
                $this->service->process($model, 'image');
            }
        }
        
        if ($model instanceof \App\Models\ProductImage) {
            // Enforce single primary image
            if ($model->is_primary) {
                \App\Models\ProductImage::where('product_id', $model->product_id)
                    ->where('id', '!=', $model->id)
                    ->update(['is_primary' => false]);
            }

            if ($model->isDirty('image_path') && $model->image_path) {
                // For ProductImage, we assume it stores file in 'image_path'
                $this->service->process($model, 'image_path');
            }
        }
    }
}
