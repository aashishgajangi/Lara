<div>
    {{-- Success is as dangerous as failure. --}}
    
    @if($relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Products</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $product)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
                        <a href="{{ route('products.show', $product->slug) }}" class="block" wire:navigate>
                            <div class="relative aspect-square overflow-hidden bg-gray-100">
                                @if($product->primary_image)
                                    <img src="{{ $product->primary_image->src }}" 
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
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
