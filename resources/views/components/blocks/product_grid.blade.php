@props(['data'])
@php
    $count = $data['count'] ?? 8;
    $products = \App\Models\Product::where('is_active', true)
        ->latest()
        ->take($count)
        ->get();
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp
<section class="py-16 px-4">
    <div class="{{ $containerClass }}">
        @if(!empty($data['heading']))
            <div class="@if($products->count() === 1) text-center mb-8 @else flex justify-between items-end mb-8 @endif">
                <h2 class="text-3xl font-bold font-heading text-gray-900 @if($products->count() === 1) mb-2 @endif">{{ $data['heading'] }}</h2>
                <a href="{{ route('products.index') }}" class="text-primary-600 hover:text-primary-700 font-medium @if($products->count() === 1) inline-block @endif" wire:navigate>View All &rarr;</a>
            </div>
        @endif

        <div class="@if($products->count() === 1) flex justify-center @else grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 @endif gap-6">
            @foreach($products as $product)
                <div class="group relative bg-white border border-gray-100 rounded-lg overflow-hidden hover:shadow-lg transition-shadow @if($products->count() === 1) w-full max-w-sm @endif">
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-gray-200 group-hover:opacity-75">
                         @if($product->images && count($product->images) > 0)
                            <img src="{{ $product->images[0]->thumbnail_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center">
                        @else
                            <div class="h-full w-full flex items-center justify-center bg-gray-100 text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-medium text-gray-900">
                            <a href="{{ route('products.show', $product->slug) }}" wire:navigate>
                                <span aria-hidden="true" class="absolute inset-0"></span>
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">{{ Str::limit($product->description, 50) }}</p>
                <div class="mt-2 flex items-center justify-between">
                    <div>
                        @if($product->discount_price && $product->discount_price < $product->price)
                            <span class="text-lg font-bold text-primary-600">₹{{ number_format($product->discount_price, 0) }}</span>
                            <span class="ml-2 text-sm text-gray-500 line-through">₹{{ number_format($product->price, 0) }}</span>
                        @else
                            <span class="text-lg font-bold text-gray-900">₹{{ number_format($product->price, 0) }}</span>
                        @endif
                    </div>
                </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
