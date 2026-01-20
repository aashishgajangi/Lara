<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;

class ColorManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-swatch';
    
    protected static ?string $navigationLabel = 'Color Management';
    
    protected static ?string $navigationGroup = 'Content Management';
    
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.color-management';
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill([
            'color_scheme' => SiteSetting::get('color_scheme', 'default'),
            'use_gradients' => SiteSetting::get('use_gradients', true),
            'color_primary_main' => SiteSetting::get('color_primary_main', '#2563eb'),
            'color_primary_light' => SiteSetting::get('color_primary_light', '#3b82f6'),
            'color_primary_dark' => SiteSetting::get('color_primary_dark', '#1d4ed8'),
            'color_secondary_main' => SiteSetting::get('color_secondary_main', '#9333ea'),
            'color_secondary_light' => SiteSetting::get('color_secondary_light', '#a855f7'),
            'color_secondary_dark' => SiteSetting::get('color_secondary_dark', '#7e22ce'),
            'color_success' => SiteSetting::get('color_success', '#22c55e'),
            'color_danger' => SiteSetting::get('color_danger', '#ef4444'),
            'color_warning' => SiteSetting::get('color_warning', '#f59e0b'),
            'color_info' => SiteSetting::get('color_info', '#06b6d4'),
            'text_on_primary' => SiteSetting::get('text_on_primary', '#ffffff'),
            'text_on_secondary' => SiteSetting::get('text_on_secondary', '#ffffff'),
        ]);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('🎨 Quick Color Presets')
                    ->description('Click any preset to instantly apply a professionally designed color scheme')
                    ->schema([
                        Forms\Components\Placeholder::make('presets')
                            ->label('')
                            ->content(new \Illuminate\Support\HtmlString('
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <button type="button" wire:click="applyPreset(\'blue\')" class="group relative overflow-hidden flex flex-col items-start gap-3 p-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-blue-500 hover:shadow-lg transition-all duration-200">
                                        <div class="flex gap-2 w-full">
                                            <div class="w-12 h-12 rounded-lg shadow-md" style="background: linear-gradient(to bottom right, #2563eb, #9333ea) !important;"></div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">Blue & Purple</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Professional</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 w-full">
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #2563eb !important;"></div>
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #9333ea !important;"></div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700 w-full">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview:</div>
                                            <div class="flex gap-1 flex-wrap">
                                                <span class="px-2 py-1 rounded text-xs text-white" style="background-color: #2563eb !important;">Button</span>
                                                <span class="px-2 py-1 rounded text-xs" style="background-color: #dbeafe !important; color: #1e40af !important;">Badge</span>
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" wire:click="applyPreset(\'green\')" class="group relative overflow-hidden flex flex-col items-start gap-3 p-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-green-500 hover:shadow-lg transition-all duration-200">
                                        <div class="flex gap-2 w-full">
                                            <div class="w-12 h-12 rounded-lg shadow-md" style="background: linear-gradient(to bottom right, #10b981, #14b8a6) !important;"></div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">Green & Teal</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Eco-Friendly</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 w-full">
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #10b981 !important;"></div>
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #14b8a6 !important;"></div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700 w-full">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview:</div>
                                            <div class="flex gap-1 flex-wrap">
                                                <span class="px-2 py-1 rounded text-xs text-white" style="background-color: #10b981 !important;">Button</span>
                                                <span class="px-2 py-1 rounded text-xs" style="background-color: #dcfce7 !important; color: #15803d !important;">Badge</span>
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" wire:click="applyPreset(\'red\')" class="group relative overflow-hidden flex flex-col items-start gap-3 p-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-red-500 hover:shadow-lg transition-all duration-200">
                                        <div class="flex gap-2 w-full">
                                            <div class="w-12 h-12 rounded-lg shadow-md" style="background: linear-gradient(to bottom right, #ef4444, #f97316) !important;"></div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">Red & Orange</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Bold & Energetic</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 w-full">
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #ef4444 !important;"></div>
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #f97316 !important;"></div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700 w-full">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview:</div>
                                            <div class="flex gap-1 flex-wrap">
                                                <span class="px-2 py-1 rounded text-xs text-white" style="background-color: #ef4444 !important;">Button</span>
                                                <span class="px-2 py-1 rounded text-xs" style="background-color: #fee2e2 !important; color: #b91c1c !important;">Badge</span>
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" wire:click="applyPreset(\'purple\')" class="group relative overflow-hidden flex flex-col items-start gap-3 p-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-purple-500 hover:shadow-lg transition-all duration-200">
                                        <div class="flex gap-2 w-full">
                                            <div class="w-12 h-12 rounded-lg shadow-md" style="background: linear-gradient(to bottom right, #a855f7, #ec4899) !important;"></div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">Purple & Pink</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Creative</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 w-full">
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #a855f7 !important;"></div>
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #ec4899 !important;"></div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700 w-full">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview:</div>
                                            <div class="flex gap-1 flex-wrap">
                                                <span class="px-2 py-1 rounded text-xs text-white" style="background-color: #a855f7 !important;">Button</span>
                                                <span class="px-2 py-1 rounded text-xs" style="background-color: #f3e8ff !important; color: #7e22ce !important;">Badge</span>
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" wire:click="applyPreset(\'orange\')" class="group relative overflow-hidden flex flex-col items-start gap-3 p-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-orange-500 hover:shadow-lg transition-all duration-200">
                                        <div class="flex gap-2 w-full">
                                            <div class="w-12 h-12 rounded-lg shadow-md" style="background: linear-gradient(to bottom right, #f97316, #eab308) !important;"></div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">Orange & Yellow</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Warm & Friendly</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 w-full">
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #f97316 !important;"></div>
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #eab308 !important;"></div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700 w-full">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview:</div>
                                            <div class="flex gap-1 flex-wrap">
                                                <span class="px-2 py-1 rounded text-xs text-white" style="background-color: #f97316 !important;">Button</span>
                                                <span class="px-2 py-1 rounded text-xs" style="background-color: #fef3c7 !important; color: #92400e !important;">Badge</span>
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" wire:click="applyPreset(\'dark\')" class="group relative overflow-hidden flex flex-col items-start gap-3 p-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-yellow-600 hover:shadow-lg transition-all duration-200">
                                        <div class="flex gap-2 w-full">
                                            <div class="w-12 h-12 rounded-lg shadow-md" style="background: linear-gradient(to bottom right, #1f2937, #111827) !important;"></div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">Dark & Gold</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Luxury</div>
                                            </div>
                                        </div>
                                        <div class="flex gap-1 w-full">
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #1f2937 !important;"></div>
                                            <div class="flex-1 h-2 rounded-full" style="background-color: #d97706 !important;"></div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-200 dark:border-gray-700 w-full">
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview:</div>
                                            <div class="flex gap-1 flex-wrap">
                                                <span class="px-2 py-1 rounded text-xs text-white" style="background-color: #1f2937 !important;">Button</span>
                                                <span class="px-2 py-1 rounded text-xs text-white" style="background-color: #d97706 !important;">Badge</span>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            ')),
                    ]),
                
                Forms\Components\Section::make('Display Options')
                    ->schema([
                        Forms\Components\Toggle::make('use_gradients')
                            ->label('Use Gradient Backgrounds')
                            ->helperText('Enable beautiful gradients for hero sections and buttons')
                            ->default(true)
                            ->live(),
                    ]),
                
                Forms\Components\Tabs::make('Color Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Primary Brand Color')
                            ->icon('heroicon-o-star')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->description('Your main brand color - used for buttons, links, and key elements')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\ColorPicker::make('color_primary_main')
                                                    ->label('Main Color')
                                                    ->default('#2563eb')
                                                    ->live()
                                                    ->afterStateUpdated(fn ($state) => $this->validateContrast($state, 'primary')),
                                                Forms\Components\ColorPicker::make('color_primary_light')
                                                    ->label('Light Variant')
                                                    ->default('#3b82f6')
                                                    ->helperText('Auto-suggested based on main color'),
                                                Forms\Components\ColorPicker::make('color_primary_dark')
                                                    ->label('Dark Variant')
                                                    ->default('#1d4ed8')
                                                    ->helperText('Auto-suggested based on main color'),
                                            ]),
                                        Forms\Components\ColorPicker::make('text_on_primary')
                                            ->label('Text Color on Primary Background')
                                            ->default('#ffffff')
                                            ->helperText('Automatically set to white or black for best readability'),
                                        Forms\Components\Placeholder::make('primary_preview')
                                            ->label('Preview')
                                            ->content(fn ($get) => view('filament.components.color-preview', [
                                                'bg' => $get('color_primary_main') ?? '#2563eb',
                                                'text' => $get('text_on_primary') ?? '#ffffff',
                                                'label' => 'Primary Button',
                                            ])),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Secondary Accent Color')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->description('Accent color for gradients and highlights')
                                    ->schema([
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\ColorPicker::make('color_secondary_main')
                                                    ->label('Main Color')
                                                    ->default('#9333ea')
                                                    ->live()
                                                    ->afterStateUpdated(fn ($state) => $this->validateContrast($state, 'secondary')),
                                                Forms\Components\ColorPicker::make('color_secondary_light')
                                                    ->label('Light Variant')
                                                    ->default('#a855f7'),
                                                Forms\Components\ColorPicker::make('color_secondary_dark')
                                                    ->label('Dark Variant')
                                                    ->default('#7e22ce'),
                                            ]),
                                        Forms\Components\ColorPicker::make('text_on_secondary')
                                            ->label('Text Color on Secondary Background')
                                            ->default('#ffffff'),
                                        Forms\Components\Placeholder::make('secondary_preview')
                                            ->label('Preview')
                                            ->content(fn ($get) => view('filament.components.color-preview', [
                                                'bg' => $get('color_secondary_main') ?? '#9333ea',
                                                'text' => $get('text_on_secondary') ?? '#ffffff',
                                                'label' => 'Secondary Button',
                                            ])),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Semantic Colors')
                            ->icon('heroicon-o-check-badge')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->description('Colors for success, error, warning, and info messages')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\ColorPicker::make('color_success')
                                                    ->label('Success (Green)')
                                                    ->default('#22c55e')
                                                    ->helperText('Used for positive actions and confirmations'),
                                                Forms\Components\ColorPicker::make('color_danger')
                                                    ->label('Danger (Red)')
                                                    ->default('#ef4444')
                                                    ->helperText('Used for errors and destructive actions'),
                                                Forms\Components\ColorPicker::make('color_warning')
                                                    ->label('Warning (Orange)')
                                                    ->default('#f59e0b')
                                                    ->helperText('Used for caution and important notices'),
                                                Forms\Components\ColorPicker::make('color_info')
                                                    ->label('Info (Cyan)')
                                                    ->default('#06b6d4')
                                                    ->helperText('Used for informational messages'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }
    
    public function applyPreset(string $preset): void
    {
        $presets = [
            'blue' => [
                'color_primary_main' => '#2563eb',
                'color_primary_light' => '#3b82f6',
                'color_primary_dark' => '#1d4ed8',
                'color_secondary_main' => '#9333ea',
                'color_secondary_light' => '#a855f7',
                'color_secondary_dark' => '#7e22ce',
                'text_on_primary' => '#ffffff',
                'text_on_secondary' => '#ffffff',
            ],
            'green' => [
                'color_primary_main' => '#10b981',
                'color_primary_light' => '#34d399',
                'color_primary_dark' => '#059669',
                'color_secondary_main' => '#14b8a6',
                'color_secondary_light' => '#2dd4bf',
                'color_secondary_dark' => '#0d9488',
                'text_on_primary' => '#ffffff',
                'text_on_secondary' => '#ffffff',
            ],
            'red' => [
                'color_primary_main' => '#ef4444',
                'color_primary_light' => '#f87171',
                'color_primary_dark' => '#dc2626',
                'color_secondary_main' => '#f97316',
                'color_secondary_light' => '#fb923c',
                'color_secondary_dark' => '#ea580c',
                'text_on_primary' => '#ffffff',
                'text_on_secondary' => '#ffffff',
            ],
            'purple' => [
                'color_primary_main' => '#a855f7',
                'color_primary_light' => '#c084fc',
                'color_primary_dark' => '#9333ea',
                'color_secondary_main' => '#ec4899',
                'color_secondary_light' => '#f472b6',
                'color_secondary_dark' => '#db2777',
                'text_on_primary' => '#ffffff',
                'text_on_secondary' => '#ffffff',
            ],
            'orange' => [
                'color_primary_main' => '#f97316',
                'color_primary_light' => '#fb923c',
                'color_primary_dark' => '#ea580c',
                'color_secondary_main' => '#eab308',
                'color_secondary_light' => '#facc15',
                'color_secondary_dark' => '#ca8a04',
                'text_on_primary' => '#ffffff',
                'text_on_secondary' => '#000000',
            ],
            'dark' => [
                'color_primary_main' => '#1f2937',
                'color_primary_light' => '#374151',
                'color_primary_dark' => '#111827',
                'color_secondary_main' => '#d97706',
                'color_secondary_light' => '#f59e0b',
                'color_secondary_dark' => '#b45309',
                'text_on_primary' => '#ffffff',
                'text_on_secondary' => '#ffffff',
            ],
        ];
        
        if (isset($presets[$preset])) {
            $this->form->fill($presets[$preset]);
            
            Notification::make()
                ->title('Preset Applied')
                ->success()
                ->body('The color scheme has been updated. Click "Save" to apply changes.')
                ->send();
        }
    }
    
    protected function validateContrast(string $color, string $type): void
    {
        // Calculate relative luminance and suggest text color
        $textColor = $this->getContrastingTextColor($color);
        
        if ($type === 'primary') {
            $this->data['text_on_primary'] = $textColor;
        } elseif ($type === 'secondary') {
            $this->data['text_on_secondary'] = $textColor;
        }
        
        // Auto-generate light and dark variants
        $variants = $this->generateColorVariants($color);
        
        if ($type === 'primary') {
            $this->data['color_primary_light'] = $variants['light'];
            $this->data['color_primary_dark'] = $variants['dark'];
        } elseif ($type === 'secondary') {
            $this->data['color_secondary_light'] = $variants['light'];
            $this->data['color_secondary_dark'] = $variants['dark'];
        }
    }
    
    protected function getContrastingTextColor(string $hexColor): string
    {
        // Remove # if present
        $hex = ltrim($hexColor, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Calculate relative luminance
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
        
        // Return white for dark colors, black for light colors
        return $luminance > 0.5 ? '#000000' : '#ffffff';
    }
    
    protected function generateColorVariants(string $hexColor): array
    {
        $hex = ltrim($hexColor, '#');
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Generate lighter variant (increase by 20%)
        $lightR = min(255, $r + ($r * 0.2));
        $lightG = min(255, $g + ($g * 0.2));
        $lightB = min(255, $b + ($b * 0.2));
        
        // Generate darker variant (decrease by 20%)
        $darkR = max(0, $r - ($r * 0.2));
        $darkG = max(0, $g - ($g * 0.2));
        $darkB = max(0, $b - ($b * 0.2));
        
        return [
            'light' => '#' . sprintf('%02x%02x%02x', $lightR, $lightG, $lightB),
            'dark' => '#' . sprintf('%02x%02x%02x', $darkR, $darkG, $darkB),
        ];
    }
    
    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value);
        }
        
        Notification::make()
            ->title('Colors Saved Successfully')
            ->success()
            ->body('Your website colors have been updated. Refresh the frontend to see changes.')
            ->send();
    }
    
    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Colors')
                ->action('save')
                ->color('primary')
                ->icon('heroicon-o-check'),
        ];
    }
}
