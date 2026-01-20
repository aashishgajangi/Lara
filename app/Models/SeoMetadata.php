<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMetadata extends Model
{
    protected $fillable = [
        'seoable_type',
        'seoable_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_title',
        'og_description',
        'og_image',
        'twitter_card',
        'schema_markup',
        'canonical_url',
    ];

    protected $casts = [
        'schema_markup' => 'array',
    ];

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
