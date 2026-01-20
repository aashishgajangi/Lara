<div class="relative">
    {{-- The whole world belongs to you. --}}
    <input 
        type="text" 
        wire:model.live.debounce.300ms="search"
        placeholder="Search products..." 
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
    
    @if(count($results) > 0)
        <div class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 max-h-96 overflow-y-auto z-50">
            @foreach($results as $product)
                <a href="{{ route('products.show', $product->slug) }}" 
                   class="flex items-center p-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0" wire:navigate>
                    @if($product->primary_image)
                        <img src="{{ $product->primary_image->src }}" 
                             alt="{{ $product->name }}"
                             class="w-12 h-12 object-cover rounded">
                    @else
                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-gray-400 text-xs">No image</span>
                        </div>
                    @endif
                    <div class="ml-3 flex-1">
                        <div class="font-medium text-gray-900">{{ $product->name }}</div>
                        <div class="text-sm text-gray-500">{{ $product->category->name ?? 'Uncategorized' }}</div>
                    </div>
                    <div class="text-blue-600 font-semibold">
                        ₹{{ number_format($product->getEffectivePrice(), 0) }}
                    </div>
                </a>
            @endforeach
        </div>
    @elseif(strlen($search) >= 2)
        <div class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 p-4 z-50">
            <p class="text-gray-500 text-center">No products found for "{{ $search }}"</p>
        </div>
    @endif
</div>
