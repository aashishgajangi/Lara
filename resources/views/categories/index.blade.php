@extends('layouts.app')

@section('content')
<div class="bg-white border-b">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
        <nav class="text-sm text-gray-600 mb-4">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900 font-medium">Categories</span>
        </nav>
        
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Our Collections</h1>
        <p class="text-gray-600 max-w-2xl">Explore our delicious range of cakes and pastries, perfect for every occasion.</p>
    </div>
</div>

<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                <a href="{{ route('categories.show', $category->slug) }}" class="block">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        @if($category->image)
                             <img src="{{ \App\Helpers\MediaHelper::resolveUrl($category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                <!-- Heroicon: cake -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.871c1.355 0 2.697.056 4.024.166C17.155 8.51 18 9.473 18 10.608v2.513M15 8.25v-1.5m-6 1.5v-1.5m12 9.75-1.5.75a3.354 3.354 0 0 1-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 0-3 0 3.354 3.354 0 0 1-3 0 1.5 1.5 0 0 1-3 0m3 0h10.5a.75.75 0 0 1 .75.75v3.75a3 3 0 0 1-3 3h-3a3 3 0 0 1-3-3v-3.75a.75.75 0 0 1 .75-.75Z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4 text-center">
                        <h2 class="text-lg font-bold text-gray-900 mb-1">{{ $category->name }}</h2>
                        @if($category->description)
                            <p class="text-gray-500 text-sm line-clamp-2">{{ Str::limit($category->description, 60) }}</p>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
