<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function __construct()
    {
        $clientId = Setting::where('key', 'google_client_id')->value('value');
        $clientSecret = Setting::where('key', 'google_client_secret')->value('value');
        $redirectUrl = Setting::where('key', 'google_redirect_url')->value('value');

        if ($clientId && $clientSecret && $redirectUrl) {
            config([
                'services.google.client_id' => $clientId,
                'services.google.client_secret' => $clientSecret,
                'services.google.redirect' => $redirectUrl,
            ]);
        }
    }
    public function redirect()
    {
        $enableGoogleAuth = Setting::where('key', 'enable_google_auth')->value('value') ?? false;
        
        if (!$enableGoogleAuth) {
            return redirect()->route('login')->with('error', 'Google login is currently disabled.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $enableGoogleAuth = Setting::where('key', 'enable_google_auth')->value('value') ?? false;
        
        if (!$enableGoogleAuth) {
            return redirect()->route('login')->with('error', 'Google login is currently disabled.');
        }

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // New Google user - create account
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => bcrypt(Str::random(16)),
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                ]);
                
                // Create customer record for new Google users
                Customer::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);
            } else {
                // Existing user logging in with Google
                $user->google_id = $googleUser->getId();
                $user->email_verified_at = $user->email_verified_at ?? now();
                $user->save();
                
                // Create customer record if it doesn't exist
                if (!$user->customer) {
                    Customer::create([
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                }
            }

            Auth::login($user);

            return redirect()->route('account.dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again.');
        }
    }
}
