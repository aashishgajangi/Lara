<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'type',
        'value',
        'group',
        'label',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public static function getAllSettings()
    {
        return \Illuminate\Support\Facades\Cache::remember('site_settings.all', 3600, function () {
            try {
                return static::all()->pluck('value', 'key');
            } catch (\Illuminate\Database\QueryException $e) {
                return collect([]);
            }
        });
    }

    public static function get(string $key, $default = null)
    {
        $settings = self::getAllSettings();
        return $settings->get($key, $default);
    }

    public static function set(string $key, $value, string $type = 'text', string $group = 'general', ?string $label = null): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => $label ?? ucwords(str_replace('_', ' ', $key)),
            ]
        );
        \Illuminate\Support\Facades\Cache::forget('site_settings.all');
    }

    public static function getMediaUrl($key, $default = null)
    {
        $value = self::get($key);
        return \App\Helpers\MediaHelper::resolveUrl($value, $default);
    }

    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group)->orderBy('sort_order');
    }
}
