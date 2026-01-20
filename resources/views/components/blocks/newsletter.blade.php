@props(['data'])
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
    $colors = \App\Services\ColorService::getColors();
@endphp
<section class="py-16 px-4" style="background-color: {{ $colors['primary']['dark'] }}; color: {{ $colors['primary']['text'] }};">
    <div class="{{ $containerClass }} text-center max-w-3xl mx-auto">
        <h2 class="text-3xl font-bold mb-4 font-heading" style="color: {{ $colors['primary']['text'] }}">{{ $data['heading'] ?? 'Subscribe to our Newsletter' }}</h2>
        <p class="mb-8" style="color: {{ $colors['primary']['text'] }}; opacity: 0.9;">{{ $data['text'] ?? 'Stay updated with our latest news and offers.' }}</p>
        
        <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
            <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
            <button type="submit" class="px-6 py-3 rounded-md font-bold transition-colors shadow-md" style="background-color: {{ $colors['primary']['text'] }}; color: {{ $colors['primary']['dark'] }};" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                Subscribe
            </button>
        </form>
    </div>
</section>
