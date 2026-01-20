<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'media_id',
        'alt_text',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(\Awcodes\Curator\Models\Media::class, 'media_id');
    }

    // Accessor for smart image retrieval
    public function getSrcAttribute()
    {
        if ($this->media_id && $this->media) {
            return $this->media->url;
        }
        
        return \App\Helpers\MediaHelper::resolveUrl($this->image_path);
    }

    public function getThumbnailUrlAttribute()
    {
        // Revert to standard URL until route issue is resolved
        if ($this->media_id && $this->media) {
            return $this->media->url;
        }

        // Fallback for legacy images
        return $this->src;
    }

    public function getLargeUrlAttribute()
    {
        if ($this->media_id && $this->media) {
            return $this->media->url;
        }

        return $this->src;
    }
}
