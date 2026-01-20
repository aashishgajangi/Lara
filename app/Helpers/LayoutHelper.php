<?php

namespace App\Helpers;

use App\Models\SiteSetting;

class LayoutHelper
{
    /**
     * Get the container width class based on site settings
     */
    public static function getContainerClass(): string
    {
        $width = SiteSetting::get('container_width', 'default');
        
        return match($width) {
            'narrow' => 'max-w-screen-lg',      // 1024px
            'default' => 'max-w-screen-xl',     // 1280px
            'wide' => 'max-w-screen-2xl',       // 1536px
            'full' => 'max-w-full',             // 100%
            default => 'max-w-screen-xl',       // fallback to default
        };
    }
    
    /**
     * Get the full container classes with mx-auto and px
     */
    public static function getContainerClasses(): string
    {
        return self::getContainerClass() . ' mx-auto px-4 sm:px-6 lg:px-8';
    }
}
