<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::saved(function ($menu) {
            \Illuminate\Support\Facades\Cache::forget('menu.' . $menu->location);
        });

        static::deleted(function ($menu) {
            \Illuminate\Support\Facades\Cache::forget('menu.' . $menu->location);
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    public static function getByLocation(string $location)
    {
        return \Illuminate\Support\Facades\Cache::remember('menu.' . $location, 3600, function () use ($location) {
            return static::with(['items' => function ($query) {
                $query->with('children')->where('is_active', true);
            }])
            ->active()
            ->location($location)
            ->first();
        });
    }
}
