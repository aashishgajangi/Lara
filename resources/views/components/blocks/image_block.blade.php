@props(['data'])
@php
    $imageUrl = isset($data['image']) ? \App\Helpers\MediaHelper::resolveUrl($data['image']) : null;
    $layout = $data['layout'] ?? 'contained';
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp
@if($imageUrl)
<section class="py-8 sm:py-12 px-4">
    <div class="{{ $layout === 'full' ? 'w-full' : $containerClass . ' max-w-5xl mx-auto' }}">
        <figure>
            <img src="{{ $imageUrl }}" alt="{{ $data['caption'] ?? 'Image' }}" class="w-full h-auto rounded-lg shadow-lg">
            @if(isset($data['caption']))
                <figcaption class="mt-2 text-center text-sm text-gray-500">{{ $data['caption'] }}</figcaption>
            @endif
        </figure>
    </div>
</section>
@endif
