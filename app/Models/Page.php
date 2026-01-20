<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_description',
        'meta_keywords',
        'content',
        'blocks',
        'template',
        'template_type',
        'sections',
        'section_data',
        'is_published',
        'is_homepage',
        'show_title',
        'sort_order',
        'seo_settings',
        'updated_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_homepage' => 'boolean',
        'show_title' => 'boolean',
        'blocks' => 'array',
        'sections' => 'array',
        'section_data' => 'array',
        'seo_settings' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::saving(function (Page $page) {
            if ($page->isDirty('title') && empty($page->getOriginal('slug'))) {
                $page->slug = Str::slug($page->title);
            }
            if (auth()->check()) {
                $page->updated_by = auth()->id();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeHomepage($query)
    {
        return $query->where('is_homepage', true);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
