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
    <header class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
    </header>
    
    <main class="w-full">
        <div class="prose prose-lg max-w-none">
            {!! $page->content !!}
        </div>
    </main>
</div>
@endsection
