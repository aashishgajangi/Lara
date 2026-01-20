<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @forelse($products as $product)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
            <a href="{{ route('products.show', $product->slug) }}" class="block">
                <div class="relative aspect-square overflow-hidden bg-gray-100">
                        @if($product->primary_image)
                            <img src="{{ $product->primary_image->thumbnail_url }}" 
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    @if($product->discount_price && $product->discount_price < $product->price)
                        <span class="absolute top-2 right-2 bg-danger-500 text-white text-xs font-bold px-2 py-1 rounded">
                            SALE
                        </span>
                    @endif
                </div>
                
                <div class="p-4">
                    <div class="text-xs text-gray-500 mb-1">{{ $product->category->name ?? 'Uncategorized' }}</div>
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            @if($product->discount_price && $product->discount_price < $product->price)
                                <span class="text-lg font-bold text-primary-600">₹{{ number_format($product->discount_price, 0) }}</span>
                                <span class="text-sm text-gray-500 line-through ml-1">₹{{ number_format($product->price, 0) }}</span>
                            @else
                                <span class="text-lg font-bold text-primary-600">₹{{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                        
                        @if($product->stock_quantity > 0)
                            <button wire:click="$dispatch('add-to-cart', { productId: {{ $product->id }} })"
                                    class="bg-primary-600 text-white p-2 rounded-lg hover:bg-primary-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </button>
                        @else
                            <span class="text-danger-500 text-sm font-medium">Out of Stock</span>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">No featured products available at the moment.</p>
        </div>
    @endforelse
</div>
{{-- If your happiness depends on money, you will never be happy with yourself. --}}
