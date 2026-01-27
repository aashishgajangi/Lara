<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\SitemapController;

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Homepage - check for CMS homepage or fallback to static home
Route::get('/', [App\Http\Controllers\PageController::class, 'homepage'])->name('home');

Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/products/{slug}', function ($slug) {
    $product = \App\Models\Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
    app(\App\Services\SeoService::class)->setProductSeo($product);
    return view('products.show', ['slug' => $slug, 'product' => $product]);
})->name('products.show');

Route::get('/categories/{slug}', function ($slug) {
    $category = \App\Models\Category::where('slug', $slug)->where('is_active', true)->firstOrFail();
    app(\App\Services\SeoService::class)->setCategorySeo($category);
    return view('categories.show', ['slug' => $slug, 'category' => $category]);
})->name('categories.show');

Route::get('/cart', function () {
    return view('cart.index');
})->name('cart.index');

Route::get('/checkout', function () {
    return view('checkout.index');
})->name('checkout')->middleware('auth');

// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update');

// Google OAuth Routes
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleAuthController::class, 'callback']);

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('account.dashboard')->with('success', 'Email verified successfully!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Customer Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/account/dashboard', function () {
        return view('account.dashboard');
    })->name('account.dashboard');
});

Route::post('/admin/upload-image', [ImageUploadController::class, 'upload'])
    ->middleware(['web', 'auth'])
    ->name('admin.upload.image');



// Dynamic CMS Pages - This must be at the end to avoid conflicts with other routes
Route::get('/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('page.show');
