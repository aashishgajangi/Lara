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
    <div class="bg-gray-50 py-16">
        <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }}">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
            </div>
        </div>
    </div>
    
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-16">
        <div class="w-full">
            <div class="prose prose-xl max-w-none">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
