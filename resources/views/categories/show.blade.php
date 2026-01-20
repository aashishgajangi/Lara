@extends('layouts.app')

@section('title', $category->name . ' - Shop by Category')

@section('content')
<div class="bg-white border-b">
    <div class="container mx-auto px-4 py-8">
        <nav class="text-sm text-gray-600 mb-4">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">{{ $category->name }}</span>
        </nav>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-gray-600 mt-2">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-8">
    @livewire('category-products', ['categorySlug' => $slug])
</div>
@endsection
