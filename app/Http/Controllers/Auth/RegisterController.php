<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $enableEmailAuth = Setting::where('key', 'enable_email_auth')->value('value') ?? true;
        $enableGoogleAuth = Setting::where('key', 'enable_google_auth')->value('value') ?? false;
        
        return view('auth.register', compact('enableEmailAuth', 'enableGoogleAuth'));
    }

    public function register(Request $request)
    {
        $enableEmailAuth = Setting::where('key', 'enable_email_auth')->value('value') ?? true;
        
        if (!$enableEmailAuth) {
            return redirect()->route('login')->with('error', 'Email registration is currently disabled.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create customer record
        Customer::create([
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        event(new Registered($user));
        
        // Send welcome email
        \Mail::to($user->email)->send(new \App\Mail\WelcomeEmail($user));

        Auth::login($user);

        return redirect()->route('account.dashboard')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }
}
