{{-- Category Grid Block --}}
@php
    $limit = $content['limit'] ?? 6;
    $categories = \App\Models\Category::where('is_active', true)
        ->whereNull('parent_id')
        ->withCount('products')
        ->orderBy('sort_order')
        ->take($limit)
        ->get();
@endphp

<section class="py-12 bg-gray-50">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }}">
        @if(!empty($content['heading']))
            <h2 class="text-3xl font-bold mb-8 text-center text-gray-900">{{ $content['heading'] }}</h2>
        @endif
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category->slug) }}" 
                   class="bg-white rounded-lg p-6 text-center hover:shadow-lg transition duration-300">
                    <div class="text-4xl mb-3">{{ $category->icon ?? '📦' }}</div>
                    <h3 class="font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $category->products_count }} items</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
