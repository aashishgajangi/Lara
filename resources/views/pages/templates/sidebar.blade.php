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
<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    <div class="flex flex-wrap -mx-4">
        <!-- Main Content -->
        <div class="w-full lg:w-2/3 px-4">
            <header class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
            </header>
            
            <main class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </main>
        </div>
        
        <!-- Sidebar -->
        <div class="w-full lg:w-1/3 px-4 mt-8 lg:mt-0">
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">Products</a></li>
                    @auth
                        <li><a href="{{ route('account.dashboard') }}" class="text-blue-600 hover:text-blue-800">My Account</a></li>
                    @endauth
                </ul>
            </div>
            
            <div class="bg-blue-50 p-6 rounded-lg mt-6">
                <h3 class="text-xl font-bold mb-4">Need Help?</h3>
                <p class="text-gray-600 mb-4">Have questions about our products or services? We're here to help!</p>
                <a href="#" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Contact Us</a>
            </div>
        </div>
    </div>
</div>
@endsection
