<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $enableEmailAuth = Setting::where('key', 'enable_email_auth')->value('value') ?? true;
        $enableGoogleAuth = Setting::where('key', 'enable_google_auth')->value('value') ?? false;
        
        return view('auth.login', compact('enableEmailAuth', 'enableGoogleAuth'));
    }

    public function login(Request $request)
    {
        $enableEmailAuth = Setting::where('key', 'enable_email_auth')->value('value') ?? true;
        
        if (!$enableEmailAuth) {
            return redirect()->route('login')->with('error', 'Email login is currently disabled.');
        }

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('account.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }
}
