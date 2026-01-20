<div>
    <div class="flex flex-col md:flex-row gap-4 mb-6">
        <input type="text" 
               wire:model.live.debounce.300ms="search"
               placeholder="Search products..." 
               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        
        <select wire:model.live="sortBy" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="latest">Latest</option>
            <option value="price_low">Price: Low to High</option>
            <option value="price_high">Price: High to Low</option>
            <option value="name">Name: A to Z</option>
        </select>
    </div>

    <div class="text-gray-600 mb-4">
        Showing {{ $products->count() }} of {{ $products->total() }} products
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                <a href="{{ route('products.show', $product->slug) }}" class="block" wire:navigate>
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
                            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
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
                                    <span class="text-lg font-bold text-blue-600">₹{{ number_format($product->discount_price, 0) }}</span>
                                    <span class="text-sm text-gray-500 line-through ml-1">₹{{ number_format($product->price, 0) }}</span>
                                @else
                                    <span class="text-lg font-bold text-blue-600">₹{{ number_format($product->price, 0) }}</span>
                                @endif
                            </div>
                            
                            @if($product->stock_quantity > 0)
                                <span class="text-green-600 text-sm">In Stock</span>
                            @else
                                <span class="text-red-500 text-sm font-medium">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">No products found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
