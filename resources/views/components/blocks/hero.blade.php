@props(['data'])

@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
    $imageUrl = isset($data['image']) ? \App\Helpers\MediaHelper::resolveUrl($data['image']) : null;
@endphp

<div class="relative w-full h-[400px] sm:h-[500px] md:h-[600px] overflow-hidden">
    @if($imageUrl)
        <img src="{{ $imageUrl }}" alt="{{ $data['title'] ?? '' }}" class="w-full h-full object-cover">
    @else
        <div class="bg-gradient-to-r from-primary-600 to-secondary-600 w-full h-full"></div>
    @endif
    
    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40">
        <div class="{{ $containerClass }} text-center text-white px-4 h-full flex flex-col justify-center items-center">
            @if(!empty($data['title']))
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold mb-4 drop-shadow-md leading-tight">{{ $data['title'] }}</h1>
            @endif
            
            @if(!empty($data['subtitle']))
                <p class="text-base sm:text-lg md:text-xl lg:text-2xl mb-8 max-w-3xl mx-auto drop-shadow-sm leading-relaxed">{{ $data['subtitle'] }}</p>
            @endif
            
            @if(!empty($data['button_text']))
                <a href="{{ $data['button_url'] ?? '#' }}" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-bold text-base sm:text-lg hover:bg-gray-100 transition shadow-lg transform hover:scale-105 duration-200" wire:navigate>
                    {{ $data['button_text'] }}
                </a>
            @endif
        </div>
    </div>
</div>
