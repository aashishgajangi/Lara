@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp

{{-- HERO SECTION --}}
@if(!empty($page->section_data['hero']))
    @php
        $hero = $page->section_data['hero'];
    @endphp
    <div class="bg-gradient-to-r from-primary-600 to-secondary-600 text-white py-16 sm:py-20 md:py-24">
        <div class="{{ $containerClass }} text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ $hero['title'] ?? 'Our Team' }}</h1>
            @if(!empty($hero['subtitle']))
                <p class="text-lg sm:text-xl md:text-2xl opacity-90">{{ $hero['subtitle'] }}</p>
            @endif
        </div>
    </div>
@endif

{{-- TEAM MEMBERS SECTION --}}
@if(!empty($page->section_data['team_members']))
    <div class="{{ $containerClass }} py-12 sm:py-16 md:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
            @foreach($page->section_data['team_members'] as $member)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-primary-500 to-secondary-600 rounded-full flex items-center justify-center text-white text-2xl sm:text-3xl font-bold">
                                {{ substr($member['name'], 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $member['name'] }}</h3>
                                <p class="text-primary-600 font-medium text-sm sm:text-base">{{ $member['role'] }}</p>
                            </div>
                        </div>
                        <p class="text-gray-700 leading-relaxed text-sm sm:text-base">{{ $member['bio'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- CTA SECTION --}}
<div class="bg-gray-50 py-12 sm:py-16">
    <div class="{{ $containerClass }} text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Want to Learn More?</h2>
        <p class="text-gray-600 mb-8 text-base sm:text-lg">Discover our story and philosophy</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/about" class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-primary-700 transition">
                About Us
            </a>
            <a href="/products" class="inline-block border border-primary-600 text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-primary-50 transition">
                Our Products
            </a>
        </div>
    </div>
</div>

@endsection
