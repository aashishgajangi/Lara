@php
    $colors = \App\Services\ColorService::getColors();
@endphp
{{-- Testimonials Block --}}
<section class="py-12" style="background-color: {{ $colors['primary']['main'] }}0D;"> {{-- 5% opacity hex --}}
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }}">
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-900">What Our Customers Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Placeholder for testimonials --}}
            <div class="bg-white p-6 rounded-lg shadow-md" style="border: 1px solid {{ $colors['primary']['light'] }}33;">
                <p class="text-gray-600 italic mb-4">"Great products and excellent service!"</p>
                <p class="font-semibold" style="color: {{ $colors['primary']['dark'] }};">- Happy Customer</p>
            </div>
        </div>
    </div>
</section>
