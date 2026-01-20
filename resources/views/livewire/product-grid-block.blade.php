<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
    @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition duration-300 group">
            <a href="{{ route('products.show', $product->slug) }}" class="block">
                <div class="relative overflow-hidden rounded-t-lg aspect-square bg-gray-100">
                    @if($product->primary_image)
                        <img src="{{ $product->primary_image->thumbnail_url }}" 
                             alt="{{ $product->primary_image->alt_text ?? $product->name }}" 
                             class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    
                    @if($product->discount_price)
                        <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                            SALE
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-1 group-hover:text-blue-600 transition">{{ $product->name }}</h3>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex flex-col">
                            @if($product->discount_price)
                                <span class="text-sm text-gray-500 line-through">₹{{ number_format($product->price, 0) }}</span>
                                <span class="text-lg font-bold text-red-600">₹{{ number_format($product->discount_price, 0) }}</span>
                            @else
                                <span class="text-lg font-bold text-gray-900">₹{{ number_format($product->price, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>
