{{-- Gallery Block --}}
<section class="py-12 bg-white">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }}">
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-900">Gallery</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Placeholder for gallery images --}}
            <div class="aspect-square bg-gray-200 rounded-lg"></div>
            <div class="aspect-square bg-gray-200 rounded-lg"></div>
            <div class="aspect-square bg-gray-200 rounded-lg"></div>
            <div class="aspect-square bg-gray-200 rounded-lg"></div>
        </div>
    </div>
</section>
