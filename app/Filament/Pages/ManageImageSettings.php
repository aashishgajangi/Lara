<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ManageImageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'System'; // Changed from Default/Empty to 'System'

    protected static ?string $navigationLabel = 'Image Optimization';

    protected static ?string $title = 'Image Optimization Settings';

    protected static string $view = 'filament.pages.manage-image-settings';
    
    protected static ?int $navigationSort = 100; // Sort after System Configuration (99)

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'image_optimization_enabled' => $this->getSetting('image_optimization_enabled', false),
            'image_format' => $this->getSetting('image_format', 'original'),
            'image_quality' => $this->getSetting('image_quality', 80),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Optimization Configuration')
                    ->description('Configure how uploaded images are processed.')
                    ->schema([
                        Toggle::make('image_optimization_enabled')
                            ->label('Enable Image Optimization')
                            ->helperText('If enabled, images will be automatically converted and optimized upon upload.')
                            ->live(),
                        
                        Select::make('image_format')
                            ->label('Target Format')
                            ->options([
                                'original' => 'Keep Original Format',
                                'webp' => 'WebP (Recommended)',
                                'avif' => 'AVIF (Best Compression)',
                            ])
                            ->default('original')
                            ->visible(fn ($get) => $get('image_optimization_enabled'))
                            ->required(),

                        TextInput::make('image_quality')
                            ->label('Quality (1-100)')
                            ->numeric()
                            ->default(80)
                            ->minValue(1)
                            ->maxValue(100)
                            ->visible(fn ($get) => $get('image_optimization_enabled'))
                            ->required(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'image_optimization']
            );
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getSetting(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
