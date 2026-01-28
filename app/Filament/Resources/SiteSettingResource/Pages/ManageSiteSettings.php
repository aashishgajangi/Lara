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
            'site_favicon' => SiteSetting::get('site_favicon'),
            'container_width' => SiteSetting::get('container_width', 'default'),
            'header_sticky' => SiteSetting::get('header_sticky', true),
            
            // Top Bar
            'top_bar_enabled' => SiteSetting::get('top_bar_enabled', true),
            'top_bar_content' => SiteSetting::get('top_bar_content', '<div class="flex justify-between items-center text-sm w-full">
                <div class="flex items-center space-x-4">
                    <span>📞 Support: +1 (555) 123-4567</span>
                    <span>📧 support@laracommerce.com</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="hover:text-gray-300">Track Order</a>
                    <a href="#" class="hover:text-gray-300">Help</a>
                </div>
            </div>'),
            'top_bar_bg_color' => SiteSetting::get('top_bar_bg_color', '#111827'),
            'top_bar_text_color' => SiteSetting::get('top_bar_text_color', '#ffffff'),
            
            // Brand Colors
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
            
            // Homepage - Hero
            'hero_background_image' => SiteSetting::get('hero_background_image'),
            'hero_image_tablet' => SiteSetting::get('hero_image_tablet'),
            'hero_image_mobile' => SiteSetting::get('hero_image_mobile'),
            'hero_title' => SiteSetting::get('hero_title', 'Welcome to LaraCommerce'),
            'hero_subtitle' => SiteSetting::get('hero_subtitle', 'Discover amazing products at great prices'),
            'hero_button_text' => SiteSetting::get('hero_button_text', 'Shop Now'),
            'hero_button_url' => SiteSetting::get('hero_button_url', '/products'),
            'homepage_content' => SiteSetting::get('homepage_content', ''),
            
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
            'social_linkedin' => SiteSetting::get('social_linkedin', ''),
            
            // Widgets - WhatsApp
            'whatsapp_enabled' => SiteSetting::get('whatsapp_enabled', false),
            'whatsapp_number' => SiteSetting::get('whatsapp_number', ''),
            'whatsapp_message' => SiteSetting::get('whatsapp_message', 'Hello! I need help with...'),
            'whatsapp_message' => SiteSetting::get('whatsapp_message', 'Hello! I need help with...'),
            'whatsapp_position' => SiteSetting::get('whatsapp_position', 'bottom-right'),

            // Organization Schema
            // Identity
            'org_schema_type' => SiteSetting::get('org_schema_type', 'Organization'),
            'org_legal_name' => SiteSetting::get('org_legal_name', SiteSetting::get('site_logo_text', 'LaraCommerce')),
            'org_alternate_name' => SiteSetting::get('org_alternate_name', ''),
            'org_description' => SiteSetting::get('org_description', ''),
            'org_slogan' => SiteSetting::get('org_slogan', ''),
            
            // Legal & Identifiers
            'org_tax_id' => SiteSetting::get('org_tax_id', ''),
            'org_vat_id' => SiteSetting::get('org_vat_id', ''),
            'org_duns' => SiteSetting::get('org_duns', ''),
            'org_iso6523' => SiteSetting::get('org_iso6523', ''),
            
            // Founding Info
            'org_founding_date' => SiteSetting::get('org_founding_date', ''),
            'org_founder' => SiteSetting::get('org_founder', ''),
            'org_founding_location' => SiteSetting::get('org_founding_location', ''),
            
            // Address Details
            'org_addr_street' => SiteSetting::get('org_addr_street', ''),
            'org_addr_city' => SiteSetting::get('org_addr_city', ''),
            'org_addr_state' => SiteSetting::get('org_addr_state', ''),
            'org_addr_postal_code' => SiteSetting::get('org_addr_postal_code', ''),
            'org_addr_country' => SiteSetting::get('org_addr_country', ''),
            
            // Contact Point Details
            'org_contact_type' => SiteSetting::get('org_contact_type', 'customer service'),
            'org_area_served' => SiteSetting::get('org_area_served', 'Worldwide'),
            'org_available_language' => SiteSetting::get('org_available_language', 'English'),
            
            // Business Details
            'org_price_range' => SiteSetting::get('org_price_range', ''),
            'org_currencies_accepted' => SiteSetting::get('org_currencies_accepted', ''),
            'org_payment_accepted' => SiteSetting::get('org_payment_accepted', ''),
            
            // Links
            'org_diversity_policy' => SiteSetting::get('org_diversity_policy', ''),
            'org_ethics_policy' => SiteSetting::get('org_ethics_policy', ''),
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Site Settings')
                    ->tabs([
                        /*
                        Forms\Components\Tabs\Tab::make('Homepage')
                            ->icon('heroicon-o-home')
                            ->schema([
                                // ... Homepage settings moved to Page Builder ...
                                Forms\Components\Placeholder::make('migration_notice')
                                    ->content('Homepage settings are now managed via the Pages > Homepage resource.'),
                            ]),
                        */
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
                                            ->directory('logos')
                                            ->helperText('Upload a logo image (optional). If not provided, site name will be used.'),
                                        Forms\Components\FileUpload::make('site_favicon')
                                            ->label('Favicon')
                                            ->image()
                                            ->acceptedFileTypes(['image/x-icon', 'image/png', 'image/svg+xml'])
                                            ->directory('favicons')
                                            ->helperText('Upload a favicon (ico, png, svg). Used for browser tab icon.'),
                                    ])
                                    ->columns(2),
                                
                                Forms\Components\Section::make('Layout Settings')
                                    ->description('Control the overall layout and width of your website')
                                    ->schema([
                                        Forms\Components\Select::make('container_width')
                                            ->label('Container Width')
                                            ->options([
                                                'default' => 'Default (1280px - Balanced)',
                                                'narrow' => 'Narrow (1024px - Compact)',
                                                'wide' => 'Wide (1536px - Spacious)',
                                                'full' => 'Full Width (100% - Edge to Edge)',
                                            ])
                                            ->default('default')
                                            ->required()
                                            ->helperText('This width applies to all pages uniformly across your website')
                                            ->columnSpanFull(),
                                        Forms\Components\Toggle::make('header_sticky')
                                            ->label('Enable Sticky Header')
                                            ->default(true)
                                            ->helperText('Keep the header visible at the top while scrolling'),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Top Bar')
                            ->icon('heroicon-o-minus')
                            ->schema([
                                Forms\Components\Section::make('Top Bar Settings')
                                    ->description('Manage the very top bar of your website')
                                    ->schema([
                                        Forms\Components\Toggle::make('top_bar_enabled')
                                            ->label('Enable Top Bar')
                                            ->default(true),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\ColorPicker::make('top_bar_bg_color')
                                                    ->label('Background Color')
                                                    ->default('#111827'),
                                                Forms\Components\ColorPicker::make('top_bar_text_color')
                                                    ->label('Text Color')
                                                    ->default('#ffffff'),
                                            ]),
                        
                                        Forms\Components\RichEditor::make('top_bar_content')
                                            ->label('Content')
                                            ->helperText('Use the editor to customize the top bar content.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Contact Info')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Forms\Components\Section::make('Contact Information')
                                    ->description('Contact details displayed in header and footer')
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
                                            ->placeholder("123 E-commerce Street\nDigital City, DC 12345\nUnited States")
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
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
                                            ->placeholder('© 2024 LaraCommerce. All rights reserved.')
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        
                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Forms\Components\Section::make('Social Media Links')
                                    ->description('Add your social media profile URLs (displayed in footer)')
                                    ->schema([
                                        Forms\Components\TextInput::make('social_facebook')
                                            ->label('Facebook')
                                            ->url()
                                            ->placeholder('https://facebook.com/yourpage')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        Forms\Components\TextInput::make('social_twitter')
                                            ->label('Twitter / X')
                                            ->url()
                                            ->placeholder('https://twitter.com/yourhandle')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        Forms\Components\TextInput::make('social_instagram')
                                            ->label('Instagram')
                                            ->url()
                                            ->placeholder('https://instagram.com/yourprofile')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                        Forms\Components\TextInput::make('social_linkedin')
                                            ->label('LinkedIn')
                                            ->url()
                                            ->placeholder('https://linkedin.com/company/yourcompany')
                                            ->prefixIcon('heroicon-o-globe-alt'),
                                    ])
                                    ->columns(2),
                            ]),
                            
                            
                        Forms\Components\Tabs\Tab::make('Organization Schema')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                Forms\Components\Section::make('Identity')
                                    ->description('Core identity information for Schema.org')
                                    ->schema([
                                        Forms\Components\Select::make('org_schema_type')
                                            ->label('Schema Type')
                                            ->options([
                                                'Organization' => 'Organization (Generic)',
                                                'LocalBusiness' => 'Local Business',
                                                'Store' => 'Store',
                                                'Bakery' => 'Bakery',
                                                'Restaurant' => 'Restaurant',
                                            ])
                                            ->default('Organization')
                                            ->required()
                                            ->live()
                                            ->columnSpanFull()
                                            ->helperText('Select "Store" or "Bakery" if you sell products. "Organization" does not support price range or payment methods.'),
                                        Forms\Components\TextInput::make('org_legal_name')
                                            ->label('Legal Name')
                                            ->helperText('The official registered name of the organization.')
                                            ->required(),
                                        Forms\Components\TextInput::make('org_alternate_name')
                                            ->label('Alternate Name')
                                            ->helperText('An alias or trading name.'),
                                        Forms\Components\Textarea::make('org_description')
                                            ->label('Description')
                                            ->rows(2),
                                        Forms\Components\TextInput::make('org_slogan')
                                            ->label('Slogan'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Location & Address')
                                    ->description('Physical location details')
                                    ->schema([
                                        Forms\Components\Textarea::make('org_addr_street')
                                            ->label('Street Address')
                                            ->rows(2)
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('org_addr_city')
                                            ->label('City'),
                                        Forms\Components\TextInput::make('org_addr_state')
                                            ->label('State/Province'),
                                        Forms\Components\TextInput::make('org_addr_postal_code')
                                            ->label('Postal Code'),
                                        Forms\Components\TextInput::make('org_addr_country')
                                            ->label('Country')
                                            ->helperText('ISO 3166-1 alpha-2 country code (e.g. US, IN)'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Legal & Identifiers')
                                    ->description('Official registration identifiers')
                                    ->schema([
                                        Forms\Components\TextInput::make('org_tax_id')
                                            ->label('Tax ID')
                                            ->helperText('e.g. TIN, EIN'),
                                        Forms\Components\TextInput::make('org_vat_id')
                                            ->label('VAT ID'),
                                        Forms\Components\TextInput::make('org_duns')
                                            ->label('DUNS Number')
                                            ->helperText('Dun & Bradstreet Number'),
                                        Forms\Components\TextInput::make('org_iso6523')
                                            ->label('ISO 6523 Code'),
                                    ])->columns(2),

                                Forms\Components\Section::make('Founding Information')
                                    ->schema([
                                        Forms\Components\DatePicker::make('org_founding_date')
                                            ->label('Founding Date'),
                                        Forms\Components\TextInput::make('org_founder')
                                            ->label('Founder(s)')
                                            ->helperText('Comma separated names'),
                                        Forms\Components\TextInput::make('org_founding_location')
                                            ->label('Founding Location'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Contact & Business Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('org_contact_type')
                                            ->label('Contact Type')
                                            ->default('customer service'),
                                        Forms\Components\TextInput::make('org_area_served')
                                            ->label('Area Served')
                                            ->default('Worldwide'),
                                        Forms\Components\TextInput::make('org_available_language')
                                            ->label('Available Language')
                                            ->default('English'),
                                        
                                        Forms\Components\TextInput::make('org_price_range')
                                            ->label('Price Range')
                                            ->placeholder('$$$')
                                            ->hidden(fn (Forms\Get $get) => $get('org_schema_type') === 'Organization'),
                                        Forms\Components\TextInput::make('org_currencies_accepted')
                                            ->label('Currencies Accepted')
                                            ->placeholder('USD, EUR, INR')
                                            ->hidden(fn (Forms\Get $get) => $get('org_schema_type') === 'Organization'),
                                        Forms\Components\TextInput::make('org_payment_accepted')
                                            ->label('Payment Methods')
                                            ->placeholder('Credit Card, PayPal')
                                            ->hidden(fn (Forms\Get $get) => $get('org_schema_type') === 'Organization'),
                                    ])->columns(3),

                                Forms\Components\Section::make('Policy Links')
                                    ->schema([
                                        Forms\Components\TextInput::make('org_diversity_policy')
                                            ->label('Diversity Policy URL')
                                            ->url(),
                                        Forms\Components\TextInput::make('org_ethics_policy')
                                            ->label('Ethics Policy URL')
                                            ->url(),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Widgets')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Forms\Components\Section::make('WhatsApp Widget')
                                    ->description('Configure the floating WhatsApp contact button')
                                    ->schema([
                                        Forms\Components\Toggle::make('whatsapp_enabled')
                                            ->label('Enable WhatsApp Widget')
                                            ->default(false),
                                        
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('whatsapp_number')
                                                    ->label('Phone Number')
                                                    ->placeholder('e.g. 15551234567')
                                                    ->helperText('Include country code without + (e.g. 15550000000)')
                                                    ->required(fn (Forms\Get $get) => $get('whatsapp_enabled')),
                                                
                                                Forms\Components\Select::make('whatsapp_position')
                                                    ->label('Position')
                                                    ->options([
                                                        'bottom-left' => 'Bottom Left',
                                                        'bottom-right' => 'Bottom Right',
                                                    ])
                                                    ->default('bottom-right')
                                                    ->required(),
                                            ]),
                                            
                                        Forms\Components\Textarea::make('whatsapp_message')
                                            ->label('Pre-filled Message')
                                            ->placeholder('Hello! I am interested in...')
                                            ->helperText('Optional text that appears when user clicks the button')
                                            ->rows(2),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        \Illuminate\Support\Facades\Log::info('Saving Site Settings', ['data' => $data]);
        
        foreach ($data as $key => $value) {
            SiteSetting::set($key, $value);
        }
        
        Notification::make()
            ->success()
            ->title('Settings saved')
            ->body('Your site settings have been saved successfully.')
            ->send();
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
