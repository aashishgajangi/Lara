<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Site Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Homepage')
                            ->schema([
                                Forms\Components\Section::make('Hero Section')
                                    ->schema([
                                        Forms\Components\TextInput::make('hero_title')
                                            ->label('Hero Title')
                                            ->default(SiteSetting::get('hero_title', 'Welcome to LaraCommerce'))
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('hero_subtitle')
                                            ->label('Hero Subtitle')
                                            ->rows(3)
                                            ->default(SiteSetting::get('hero_subtitle', 'Discover amazing products at great prices'))
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('hero_button_text')
                                            ->label('Hero Button Text')
                                            ->default(SiteSetting::get('hero_button_text', 'Shop Now')),
                                        Forms\Components\TextInput::make('hero_button_url')
                                            ->label('Hero Button URL')
                                            ->default(SiteSetting::get('hero_button_url', '/products')),
                                        Forms\Components\FileUpload::make('hero_background_image')
                                            ->label('Hero Background Image')
                                            ->image()
                                            ->directory('hero')
                                            ->columnSpanFull(),
                                    ]),
                                Forms\Components\Section::make('Homepage Content')
                                    ->schema([
                                        Forms\Components\RichEditor::make('homepage_content')
                                            ->label('Additional Homepage Content')
                                            ->columnSpanFull()
                                            ->default(SiteSetting::get('homepage_content', '<h2>Featured Products</h2><p>Check out our latest and greatest products.</p>')),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Header & Footer')
                            ->schema([
                                Forms\Components\Section::make('Header Settings')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_logo_text')
                                            ->label('Site Logo Text')
                                            ->default(SiteSetting::get('site_logo_text', 'LaraCommerce')),
                                        Forms\Components\FileUpload::make('site_logo_image')
                                            ->label('Site Logo Image')
                                            ->image()
                                            ->directory('logos'),
                                        Forms\Components\TextInput::make('header_phone')
                                            ->label('Header Phone')
                                            ->default(SiteSetting::get('header_phone', '+1 (555) 123-4567')),
                                        Forms\Components\TextInput::make('header_email')
                                            ->label('Header Email')
                                            ->email()
                                            ->default(SiteSetting::get('header_email', 'support@laracommerce.com')),
                                        Forms\Components\Toggle::make('header_sticky')
                                            ->label('Enable Sticky Header')
                                            ->default(SiteSetting::get('header_sticky', true)),
                                    ]),
                                Forms\Components\Section::make('Footer Settings')
                                    ->schema([
                                        Forms\Components\RichEditor::make('footer_about')
                                            ->label('Footer About Text')
                                            ->columnSpanFull()
                                            ->default(SiteSetting::get('footer_about', '<p>LaraCommerce is your trusted e-commerce partner, providing quality products and exceptional service since 2024.</p>')),
                                        Forms\Components\TextInput::make('footer_copyright')
                                            ->label('Copyright Text')
                                            ->default(SiteSetting::get('footer_copyright', '© 2024 LaraCommerce. All rights reserved.'))
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('footer_address')
                                            ->label('Footer Address')
                                            ->rows(3)
                                            ->default(SiteSetting::get('footer_address', "123 E-commerce Street\nDigital City, DC 12345\nUnited States"))
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Navigation')
                            ->schema([
                                Forms\Components\Section::make('Main Navigation Menu')
                                    ->schema([
                                        Forms\Components\Repeater::make('main_navigation')
                                            ->label('Navigation Items')
                                            ->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->required()
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('url')
                                                    ->required()
                                                    ->columnSpan(1),
                                                Forms\Components\Toggle::make('opens_new_tab')
                                                    ->label('Open in New Tab')
                                                    ->columnSpan(1),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(0)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->schema([
                                Forms\Components\Section::make('Social Media Links')
                                    ->schema([
                                        Forms\Components\TextInput::make('social_facebook')
                                            ->label('Facebook URL')
                                            ->url()
                                            ->default(SiteSetting::get('social_facebook', '')),
                                        Forms\Components\TextInput::make('social_twitter')
                                            ->label('Twitter URL')
                                            ->url()
                                            ->default(SiteSetting::get('social_twitter', '')),
                                        Forms\Components\TextInput::make('social_instagram')
                                            ->label('Instagram URL')
                                            ->url()
                                            ->default(SiteSetting::get('social_instagram', '')),
                                        Forms\Components\TextInput::make('social_linkedin')
                                            ->label('LinkedIn URL')
                                            ->url()
                                            ->default(SiteSetting::get('social_linkedin', '')),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('group')
                    ->colors([
                        'primary' => 'general',
                        'success' => 'homepage',
                        'warning' => 'header',
                        'danger' => 'footer',
                        'info' => 'navigation',
                    ]),
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'homepage' => 'Homepage',
                        'header' => 'Header',
                        'footer' => 'Footer',
                        'navigation' => 'Navigation',
                    ]),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'richtext' => 'Rich Text',
                        'image' => 'Image',
                        'json' => 'JSON',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('group')
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSiteSettings::route('/'),
        ];
    }
}
