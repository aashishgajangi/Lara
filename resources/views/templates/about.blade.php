@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp

{{-- HERO SECTION --}}
@if(
    ($page->sections['hero'] ?? true) && 
    !empty($page->section_data['hero']) && 
    ($page->show_title || !empty($page->section_data['hero']['subtitle']))
)
    @php
        $hero = $page->section_data['hero'];
    @endphp
    <div class="bg-gradient-to-r from-primary-600 to-secondary-600 text-white py-16 sm:py-20 md:py-24">
        <div class="{{ $containerClass }} text-center">
            @if($page->show_title)
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ $hero['title'] ?? $page->title }}</h1>
            @endif
            @if(!empty($hero['subtitle']))
                <p class="text-lg sm:text-xl md:text-2xl opacity-90 italic">{{ $hero['subtitle'] }}</p>
            @endif
        </div>
    </div>
@endif

{{-- STORY SECTION --}}
@if(!empty($page->section_data['story']))
    @php
        $story = $page->section_data['story'];
        $storyImage = !empty($story['image']) ? \App\Helpers\MediaHelper::resolveUrl($story['image']) : null;
    @endphp
    <div class="{{ $containerClass }} py-12 sm:py-16 md:py-20">
        <div class="grid grid-cols-1 {{ $storyImage ? 'lg:grid-cols-2 gap-12 lg:gap-20 items-center' : '' }}">
            
            {{-- Text Column --}}
            <div class="{{ $storyImage ? 'order-2 lg:order-1' : '' }}">
                @if(!empty($story['heading']))
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 sm:mb-8">{{ $story['heading'] }}</h2>
                @endif
                <div class="text-lg md:text-xl text-gray-700 leading-relaxed space-y-6">
                    {!! $story['content'] ?? '' !!}
                </div>
            </div>

            {{-- Image Column --}}
            @if($storyImage)
                <div class="order-1 lg:order-2 mb-8 lg:mb-0">
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl">
                        <img src="{{ $storyImage }}" alt="{{ $story['heading'] ?? 'Our Story' }}" class="w-full h-auto object-cover transform hover:scale-105 transition duration-700 ease-in-out">
                    </div>
                </div>
            @endif

        </div>
    </div>
@endif

{{-- PHILOSOPHY SECTION --}}
@if(!empty($page->section_data['philosophy']) || !empty($page->section_data['values']))
    @php
        $philosophy = $page->section_data['philosophy'] ?? $page->section_data['values'];
    @endphp
    <div class="bg-gray-50 py-12 sm:py-16 md:py-20">
        <div class="{{ $containerClass }}">
            @if(!empty($philosophy['heading']))
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-8 sm:mb-12 text-center">{{ $philosophy['heading'] }}</h2>
            @endif
            
            @if(!empty($philosophy['items']))
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($philosophy['items'] as $item)
                        <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 hover:shadow-xl transition-shadow duration-300">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-green-500 to-blue-600 rounded-full flex items-center justify-center mb-4 sm:mb-6">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">{{ $item['title'] }}</h3>
                            <p class="text-gray-700 leading-relaxed text-sm sm:text-base">{{ $item['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif

{{-- CTA SECTION --}}
{{-- 
<div class="{{ $containerClass }} py-12 sm:py-16 text-center">
    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Ready to Experience Pure Food?</h2>
    <p class="text-gray-600 mb-8 text-base sm:text-lg">Discover our range of authentic, lab-tested products</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="/products" class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
            Shop Products
        </a>
        <a href="/team" class="inline-block border border-green-600 text-green-600 px-8 py-3 rounded-lg font-semibold hover:bg-green-50 transition">
            Meet Our Team
        </a>
    </div>
</div> 
--}}

@endsection
