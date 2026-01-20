@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp

{{-- HERO SECTION --}}
@if(($page->sections['hero'] ?? false) && !empty($page->section_data['hero']))
    @php
        $hero = $page->section_data['hero'];
        $heroImage = \App\Helpers\MediaHelper::resolveUrl($hero['image'] ?? null);
    @endphp
    <div class="relative w-full h-[400px] sm:h-[500px] md:h-[600px] overflow-hidden">
        @if($heroImage)
            <img src="{{ $heroImage }}" alt="{{ $hero['title'] ?? '' }}" class="w-full h-full object-cover">
        @else
            <div class="bg-gradient-to-r from-primary-600 to-secondary-600 w-full h-full"></div>
        @endif
        
        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40">
            <div class="{{ $containerClass }} text-center text-white px-4">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 break-words">{{ $hero['title'] ?? 'Welcome' }}</h1>
                @if(!empty($hero['subtitle']))
                    <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-6 sm:mb-8 break-words">{{ $hero['subtitle'] }}</p>
                @endif
                @if(!empty($hero['button_text']))
                    <a href="{{ $hero['button_url'] ?? '#' }}" class="bg-white text-primary-600 px-6 sm:px-8 py-2 sm:py-3 rounded-lg font-semibold hover:bg-gray-100 transition inline-block text-sm sm:text-base" wire:navigate>
                        {{ $hero['button_text'] }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif


{{-- FEATURED PRODUCTS SECTION --}}
@if(($page->sections['products'] ?? false) && !empty($page->section_data['products']))
    @php
        $products = $page->section_data['products'];
    @endphp
    <div class="{{ $containerClass }} py-8 sm:py-12">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $products['heading'] ?? 'Featured Products' }}</h2>
            <a href="{{ route('products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm sm:text-base whitespace-nowrap">
                View All →
            </a>
        </div>
        @livewire('featured-products', ['count' => $products['count'] ?? 8])
    </div>
@endif

{{-- CATEGORIES SECTION --}}
@if($page->sections['categories'] ?? false)
    <div class="{{ $containerClass }} py-8 sm:py-12">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Shop by Category</h2>
        @livewire('category-grid')
    </div>
@endif

{{-- NEWSLETTER SECTION --}}
@if(($page->sections['newsletter'] ?? false) && !empty($page->section_data['newsletter']))
    @php
        $newsletter = $page->section_data['newsletter'];
    @endphp
    <div class="{{ $containerClass }} py-8 sm:py-12">
        <div class="bg-primary-600 text-white rounded-xl sm:rounded-2xl p-6 sm:p-8 md:p-12 text-center">
            <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">{{ $newsletter['heading'] ?? 'Join Our Newsletter' }}</h2>
            <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8">{{ $newsletter['description'] ?? 'Get exclusive deals and updates' }}</p>
            <form class="max-w-md mx-auto flex flex-col sm:flex-row gap-2 sm:gap-0">
                <input type="email" placeholder="Enter your email" class="flex-1 px-4 sm:px-6 py-2 sm:py-3 rounded-lg sm:rounded-l-lg sm:rounded-r-none text-gray-900 focus:outline-none text-sm sm:text-base">
                <button type="submit" class="bg-gray-900 text-white px-6 sm:px-8 py-2 sm:py-3 rounded-lg sm:rounded-l-none sm:rounded-r-lg hover:bg-gray-800 transition font-semibold text-sm sm:text-base whitespace-nowrap">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
@endif

@endsection
