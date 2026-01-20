@props(['data'])

@php
    $imageDesktop = $data['image_desktop'] ?? null;
    $imageTablet = $data['image_tablet'] ?? $imageDesktop;
    $imageMobile = $data['image_mobile'] ?? $imageTablet;
@endphp

    <div class="relative w-full h-screen">
    @if($imageDesktop)
        <picture>
            <source media="(max-width: 640px)" srcset="{{ \App\Helpers\MediaHelper::resolveUrl($imageMobile) }}">
            <source media="(max-width: 1024px)" srcset="{{ \App\Helpers\MediaHelper::resolveUrl($imageTablet) }}">
            <img src="{{ \App\Helpers\MediaHelper::resolveUrl($imageDesktop) }}" alt="Hero" class="w-full h-full object-cover object-center">
        </picture>
    @else
        <div class="bg-gray-200 w-full h-full"></div>
    @endif

    <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-40">
        <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ $data['title'] }}</h1>
            @if(!empty($data['subtitle']))
                <p class="text-lg md:text-2xl mb-8">{{ $data['subtitle'] }}</p>
            @endif
            
            @if(!empty($data['button_text']) && !empty($data['button_url']))
                <a href="{{ $data['button_url'] }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition" wire:navigate>
                    {{ $data['button_text'] }}
                </a>
            @endif
        </div>
    </div>
</div>
