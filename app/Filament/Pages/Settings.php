<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use App\Models\Setting;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationLabel = 'System Configuration';
    protected static ?int $navigationSort = 99;
    protected static string $view = 'filament.pages.settings';
    protected static ?string $title = 'System Configuration';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $this->form->fill($settings);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings')
                    ->tabs([
                        Tabs\Tab::make('SMTP Configuration')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                TextInput::make('smtp_host')
                                    ->label('SMTP Host')
                                    ->placeholder('smtp.gmail.com')
                                    ->required(),
                                
                                TextInput::make('smtp_port')
                                    ->label('SMTP Port')
                                    ->placeholder('587')
                                    ->numeric()
                                    ->required(),
                                
                                Select::make('smtp_encryption')
                                    ->label('Encryption')
                                    ->options([
                                        'tls' => 'TLS',
                                        'ssl' => 'SSL',
                                    ])
                                    ->default('tls')
                                    ->required(),
                                
                                TextInput::make('smtp_username')
                                    ->label('SMTP Username')
                                    ->placeholder('your-email@gmail.com')
                                    ->email()
                                    ->required(),
                                
                                TextInput::make('smtp_password')
                                    ->label('SMTP Password')
                                    ->password()
                                    ->revealable()
                                    ->required(),
                                
                                TextInput::make('mail_from_address')
                                    ->label('From Email Address')
                                    ->email()
                                    ->required(),
                                
                                TextInput::make('mail_from_name')
                                    ->label('From Name')
                                    ->placeholder('LaraCommerce')
                                    ->required(),
                            ]),
                        
                        Tabs\Tab::make('Authentication')
                            ->icon('heroicon-o-lock-closed')
                            ->schema([
                                Toggle::make('enable_email_auth')
                                    ->label('Enable Email/Password Login')
                                    ->default(true)
                                    ->helperText('Allow customers to register and login with email and password'),
                                
                                Toggle::make('enable_google_auth')
                                    ->label('Enable Google OAuth Login')
                                    ->default(false)
                                    ->helperText('Allow customers to login with their Google account')
                                    ->reactive(),
                                
                                TextInput::make('google_client_id')
                                    ->label('Google Client ID')
                                    ->placeholder('Your Google OAuth Client ID')
                                    ->visible(fn ($get) => $get('enable_google_auth')),
                                
                                TextInput::make('google_client_secret')
                                    ->label('Google Client Secret')
                                    ->password()
                                    ->revealable()
                                    ->visible(fn ($get) => $get('enable_google_auth')),
                                
                                TextInput::make('google_redirect_url')
                                    ->label('Google Redirect URL')
                                    ->placeholder('http://127.0.0.1:8000/auth/google/callback')
                                    ->helperText('Add this URL to your Google OAuth app')
                                    ->visible(fn ($get) => $get('enable_google_auth')),
                            ]),
                    ])
                    ->columnSpanFull()
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        // Update .env file for SMTP settings
        $this->updateEnvFile([
            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => $data['smtp_host'] ?? '',
            'MAIL_PORT' => $data['smtp_port'] ?? '',
            'MAIL_USERNAME' => $data['smtp_username'] ?? '',
            'MAIL_PASSWORD' => $data['smtp_password'] ?? '',
            'MAIL_ENCRYPTION' => $data['smtp_encryption'] ?? 'tls',
            'MAIL_FROM_ADDRESS' => $data['mail_from_address'] ?? '',
            'MAIL_FROM_NAME' => '"' . ($data['mail_from_name'] ?? 'LaraCommerce') . '"',
        ]);

        // Update Google OAuth settings
        if (!empty($data['google_client_id'])) {
            $this->updateEnvFile([
                'GOOGLE_CLIENT_ID' => $data['google_client_id'],
                'GOOGLE_CLIENT_SECRET' => $data['google_client_secret'] ?? '',
                'GOOGLE_REDIRECT_URL' => $data['google_redirect_url'] ?? '',
            ]);
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->send();
    }

    protected function updateEnvFile(array $data): void
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envFile, $envContent);
    }
}
