@extends('layouts.app')

@section('content')
@php
    $errorPage = \App\Models\Page::where('slug', '404-error')->where('is_published', true)->first();
    $title = $errorPage->section_data['title'] ?? '404 - Page Not Found';
    $message = $errorPage->section_data['message'] ?? "Sorry, the page you're looking for doesn't exist.";
    $buttonText = $errorPage->section_data['button_text'] ?? 'Go to Homepage';
    $buttonUrl = $errorPage->section_data['button_url'] ?? route('home');
    $showSearch = $errorPage->section_data['show_search'] ?? true;
@endphp

<div class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-2xl w-full text-center">
        <!-- 404 Number -->
        <div class="mb-8">
            <h1 class="text-9xl md:text-[12rem] font-bold text-gray-200 leading-none">404</h1>
        </div>
        
        <!-- Title -->
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            {{ $title }}
        </h2>
        
        <!-- Message -->
        <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
            {{ $message }}
        </p>
        
        @if($showSearch)
            <!-- Search Box -->
            <div class="mb-8 max-w-md mx-auto">
                <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                    <input type="text" 
                           name="search" 
                           placeholder="Search for products..." 
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                        Search
                    </button>
                </form>
            </div>
        @endif
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="{{ $buttonUrl }}" 
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                {{ $buttonText }}
            </a>
            
            <a href="{{ route('products.index') }}" 
               class="inline-block border border-blue-600 text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                Browse Products
            </a>
        </div>
        
        <!-- Popular Links -->
        <div class="mt-12 pt-8 border-t">
            <p class="text-sm text-gray-500 mb-4">You might be interested in:</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700 text-sm">Home</a>
                <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-700 text-sm">All Products</a>
                <a href="/about" class="text-blue-600 hover:text-blue-700 text-sm">About</a>
                <a href="/contact" class="text-blue-600 hover:text-blue-700 text-sm">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection
