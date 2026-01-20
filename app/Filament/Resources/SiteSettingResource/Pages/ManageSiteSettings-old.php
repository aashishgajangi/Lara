<?php

namespace App\Filament\Resources\SiteSettingResource\Pages;

use App\Filament\Resources\SiteSettingResource;
use App\Models\SiteSetting;
use Filament\Actions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Forms;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SiteSettingResource::class;

    protected static string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getDefaultData());
    }

    protected function getDefaultData(): array
    {
        return [
            // General Settings
            'site_logo_text' => SiteSetting::get('site_logo_text', 'LaraCommerce'),
            'site_logo_image' => SiteSetting::get('site_logo_image'),
            
            // Contact Information
            'header_phone' => SiteSetting::get('header_phone', '+1 (555) 123-4567'),
            'header_email' => SiteSetting::get('header_email', 'support@laracommerce.com'),
            'footer_address' => SiteSetting::get('footer_address', "123 E-commerce Street\nDigital City, DC 12345\nUnited States"),
            
            // Footer
            'footer_about' => SiteSetting::get('footer_about', '<p>LaraCommerce is your trusted e-commerce partner, providing quality products and exceptional service since 2024.</p>'),
            'footer_copyright' => SiteSetting::get('footer_copyright', '© 2024 LaraCommerce. All rights reserved.'),
            
            // Social Media
            'social_facebook' => SiteSetting::get('social_facebook', ''),
            'social_twitter' => SiteSetting::get('social_twitter', ''),
            'social_instagram' => SiteSetting::get('social_instagram', ''),
            'social_linkedin' => SiteSetting::get('social_linkedin', ''),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Site Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Forms\Components\Section::make('Site Identity')
                                    ->description('Configure your site logo and branding')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_logo_text')
                                            ->label('Site Name')
                                            ->required()
                                            ->placeholder('LaraCommerce'),
                                        Forms\Components\FileUpload::make('site_logo_image')
                                            ->label('Logo Image')
                                            ->image()
                                            ->directory('logo')
                                            ->helperText('Upload a logo image (optional). If not provided, site name will be used.'),
                                    ])
                                    ->columns(2),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Contact Info')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\Section::make('Contact Information')
                                    ->description('Contact details displayed in header and footer')
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('header_phone')
                                                    ->label('Header Phone')
                                                    ->tel(),
                                                Forms\Components\TextInput::make('header_email')
                                                    ->label('Header Email')
                                                    ->email(),
                                            ]),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Header & Footer')
                            ->schema([
                                Forms\Components\Section::make('Header Settings')
                                    ->description('Customize your site header')
                                    ->schema([
                                        Forms\Components\TextInput::make('site_logo_text')
                                            ->label('Site Logo Text')
                                            ->required(),
                                        Forms\Components\FileUpload::make('site_logo_image')
                                            ->label('Site Logo Image (Optional)')
                                            ->image()
                                            ->directory('logos')
                                            ->helperText('Leave empty to use text logo'),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('header_phone')
                                                    ->label('Phone Number')
                                                    ->tel()
                                                    ->placeholder('+1 (555) 123-4567'),
                                                Forms\Components\TextInput::make('header_email')
                                                    ->label('Email Address')
                                                    ->email()
                                                    ->placeholder('support@laracommerce.com'),
                                                Forms\Components\Textarea::make('footer_address')
                                                    ->label('Physical Address')
                                                    ->rows(3)
                                                    ->placeholder("123 E-commerce Street\nDigital City, DC 12345\nUnited States"),
                                            ])
                                            ->columns(2),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Footer')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Section::make('Footer Content')
                                    ->description('Content displayed in the footer')
                                    ->schema([
                                        Forms\Components\RichEditor::make('footer_about')
                                            ->label('About Text')
                                            ->columnSpanFull()
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'link',
                                            ])
                                            ->placeholder('Brief description about your company'),
                                        Forms\Components\TextInput::make('footer_copyright')
                                            ->label('Copyright Text')
                                            ->placeholder(' 2024 LaraCommerce. All rights reserved.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make('Social Media Links')
                                    ->description('Add your social media profile links')
                                    ->schema([
                                        Forms\Components\TextInput::make('social_facebook')
                                            ->label('Facebook URL')
                                            ->url()
                                            ->placeholder('https://facebook.com/yourpage'),
                                        Forms\Components\TextInput::make('social_twitter')
                                            ->label('Twitter URL')
                                            ->url()
                                            ->placeholder('https://twitter.com/yourhandle'),
                                        Forms\Components\TextInput::make('social_instagram')
                                            ->label('Instagram URL')
                                            ->url()
                                            ->placeholder('https://instagram.com/yourhandle'),
                                        Forms\Components\TextInput::make('social_linkedin')
                                            ->label('LinkedIn URL')
                                            ->url()
                                            ->placeholder('https://linkedin.com/company/yourcompany'),
                                    ])->columns(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if ($key === 'main_navigation') {
                $value = json_encode($value);
            }
            
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $this->getFieldType($key),
                    'group' => $this->getFieldGroup($key),
                    'label' => $this->getFieldLabel($key),
                    'description' => $this->getFieldDescription($key),
                ]
            );
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully!')
            ->body('Your site settings have been updated.')
            ->send();
    }

    protected function getFieldType(string $key): string
    {
        $richTextFields = ['homepage_content', 'footer_about'];
        $textareaFields = ['hero_subtitle', 'footer_address'];
        $imageFields = ['hero_background_image', 'site_logo_image'];
        $jsonFields = ['main_navigation'];

        if (in_array($key, $richTextFields)) return 'richtext';
        if (in_array($key, $textareaFields)) return 'textarea';
        if (in_array($key, $imageFields)) return 'image';
        if (in_array($key, $jsonFields)) return 'json';

        return 'text';
    }

    protected function getFieldGroup(string $key): string
    {
        $homepageFields = ['hero_title', 'hero_subtitle', 'hero_button_text', 'hero_button_url', 'hero_background_image', 'homepage_content'];
        $headerFields = ['site_logo_text', 'site_logo_image', 'header_phone', 'header_email'];
        $footerFields = ['footer_about', 'footer_copyright', 'footer_address'];
        $navigationFields = ['main_navigation'];
        $socialFields = ['social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin'];

        if (in_array($key, $homepageFields)) return 'homepage';
        if (in_array($key, $headerFields)) return 'header';
        if (in_array($key, $footerFields)) return 'footer';
        if (in_array($key, $navigationFields)) return 'navigation';
        if (in_array($key, $socialFields)) return 'social';

        return 'general';
    }

    protected function getFieldLabel(string $key): string
    {
        return ucwords(str_replace('_', ' ', $key));
    }

    protected function getFieldDescription(string $key): ?string
    {
        return match($key) {
            'hero_title' => 'Main headline for your homepage hero section',
            'hero_subtitle' => 'Supporting text for your hero section',
            'main_navigation' => 'Main navigation menu items',
            default => null,
        };
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('save')
                ->label('Save Settings')
                ->color('success')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}
