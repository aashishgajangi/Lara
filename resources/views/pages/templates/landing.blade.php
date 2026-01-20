@extends('layouts.app')

@section('title', $page->title)

@section('meta')
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    @if($page->seo_settings)
        @foreach($page->seo_settings as $property => $content)
            <meta name="{{ $property }}" content="{{ $content }}">
        @endforeach
    @endif
@endsection

@section('content')
<div class="w-full">
    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-blue-600 to-purple-700 text-white py-20">
        <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} text-center">
            <h1 class="text-6xl font-bold mb-6">{{ $page->title }}</h1>
            @if($page->meta_description)
                <p class="text-xl mb-8 max-w-2xl mx-auto opacity-90">{{ $page->meta_description }}</p>
            @endif
            <a href="#content" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300">
                Learn More
            </a>
        </div>
    </div>
    
    <!-- Content Section -->
    <div id="content" class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-16">
        <div class="w-full">
            <div class="prose prose-xl max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </div>
    
    <!-- Call to Action -->
    <div class="bg-gray-100 py-16">
        <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} text-center">
            <h2 class="text-3xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-gray-600 mb-8">Join thousands of satisfied customers today.</p>
            <div class="space-x-4">
                <a href="{{ route('products.index') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-700 transition duration-300">
                    Shop Now
                </a>
                @guest
                    <a href="{{ route('register') }}" class="inline-block border-2 border-blue-600 text-blue-600 px-8 py-3 rounded-full font-semibold hover:bg-blue-600 hover:text-white transition duration-300">
                        Sign Up
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
