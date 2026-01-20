@props(['data'])
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp

<section class="py-16 bg-primary-50">
    <div class="{{ $containerClass }} px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            @foreach($data['items'] as $item)
                <div class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="text-4xl font-bold text-primary-600 mb-2">{{ $item['value'] }}</div>
                    <div class="text-gray-600 font-medium">{{ $item['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
