<?php

namespace App\Services;

use App\Models\SiteSetting;

class ColorService
{
    /**
     * Get color configuration from database or defaults
     */
    public static function getColors(): array
    {
        return [
            'primary' => [
                'main' => SiteSetting::get('color_primary_main', '#2563eb'),
                'light' => SiteSetting::get('color_primary_light', '#3b82f6'),
                'dark' => SiteSetting::get('color_primary_dark', '#1d4ed8'),
                'text' => SiteSetting::get('text_on_primary', '#ffffff'),
            ],
            'secondary' => [
                'main' => SiteSetting::get('color_secondary_main', '#9333ea'),
                'light' => SiteSetting::get('color_secondary_light', '#a855f7'),
                'dark' => SiteSetting::get('color_secondary_dark', '#7e22ce'),
                'text' => SiteSetting::get('text_on_secondary', '#ffffff'),
            ],
            'success' => SiteSetting::get('color_success') ?: '#22c55e',
            'danger' => SiteSetting::get('color_danger') ?: '#ef4444',
            'warning' => SiteSetting::get('color_warning') ?: '#f59e0b',
            'info' => SiteSetting::get('color_info') ?: '#06b6d4',
            'useGradients' => SiteSetting::get('use_gradients', true),
            'topBar' => [
                'bg' => SiteSetting::get('top_bar_bg_color', '#111827'),
                'text' => SiteSetting::get('top_bar_text_color', '#ffffff'),
            ],
        ];
    }

    /**
     * Get CSS variables for inline styles
     */
    public static function getCssVariables(): string
    {
        $colors = self::getColors();
        
        return "
            --color-primary: {$colors['primary']['main']};
            --color-primary-main: {$colors['primary']['main']};
            --color-primary-light: {$colors['primary']['light']};
            --color-primary-dark: {$colors['primary']['dark']};
            --color-secondary: {$colors['secondary']['main']};
            --color-secondary-light: {$colors['secondary']['light']};
            --color-secondary-dark: {$colors['secondary']['dark']};
            --color-success: {$colors['success']};
            --color-danger: {$colors['danger']};
            --color-warning: {$colors['warning']};
            --color-info: {$colors['info']};
            --color-topbar-bg: {$colors['topBar']['bg']};
            --color-topbar-text: {$colors['topBar']['text']};
        ";
    }

    /**
     * Calculate contrasting text color (white or black) based on background color
     */
    public static function getContrastingTextColor(string $hexColor): string
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate relative luminance (perceived brightness)
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return white for dark backgrounds, black for light backgrounds
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }

    /**
     * Get Filament color configuration
     */
    public static function getFilamentColors(): array
    {
        $primaryColor = SiteSetting::get('color_primary_main', '#2563eb') ?? '#2563eb';
        $successColor = SiteSetting::get('color_success', '#22c55e') ?? '#22c55e';
        $dangerColor = SiteSetting::get('color_danger', '#ef4444') ?? '#ef4444';
        $warningColor = SiteSetting::get('color_warning', '#f59e0b') ?? '#f59e0b';
        $infoColor = SiteSetting::get('color_info', '#06b6d4') ?? '#06b6d4';
        
        return [
            'primary' => \Filament\Support\Colors\Color::hex($primaryColor),
            'success' => \Filament\Support\Colors\Color::hex($successColor),
            'danger' => \Filament\Support\Colors\Color::hex($dangerColor),
            'warning' => \Filament\Support\Colors\Color::hex($warningColor),
            'info' => \Filament\Support\Colors\Color::hex($infoColor),
        ];
    }
}
