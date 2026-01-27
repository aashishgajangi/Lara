<div>
    {{-- The Master doesn't talk, he acts. --}}
    <nav class="text-sm text-gray-600 mb-6">
        <a href="{{ route('home') }}" class="hover:text-blue-600" wire:navigate>Home</a>
        <span class="mx-2">/</span>
        <a href="{{ route('categories.show', $product->category->slug) }}" class="hover:text-blue-600" wire:navigate>
            {{ $product->category->name }}
        </a>
        <span class="mx-2">/</span>
        <span class="text-gray-900">{{ $product->name }}</span>
    </nav>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Image Gallery -->
        <div x-data="{ selectedImage: '{{ $selectedImage }}' }">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                @if($selectedImage)
                    <img :src="selectedImage" 
                         alt="{{ $product->name }}"
                         class="w-full aspect-square object-cover">
                @else
                    <div class="w-full aspect-square bg-gray-200 flex items-center justify-center">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>

            @if($product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                        <button @click="selectedImage = '{{ $image->large_url }}'"
                                :class="selectedImage === '{{ $image->large_url }}' ? 'border-blue-600' : 'border-gray-200'"
                                class="bg-white rounded-lg overflow-hidden border-2 hover:border-blue-400 transition">
                            <img src="{{ $image->thumbnail_url }}" 
                                 alt="{{ $image->alt_text }}"
                                 class="w-full aspect-square object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
            
            <div class="flex items-center gap-4 mb-4">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                    @endfor
                    <span class="ml-2 text-gray-600">({{ $reviews->total() }} reviews)</span>
                </div>
                
                @if($product->stock_quantity > 0)
                    <span class="text-green-600 font-medium">In Stock ({{ $product->stock_quantity }} available)</span>
                @else
                    <span class="text-red-600 font-medium">Out of Stock</span>
                @endif
            </div>

            <div class="mb-6">
                @if($product->discount_price && $product->discount_price < $product->price)
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-bold text-primary-600">₹{{ number_format($product->discount_price, 0) }}</span>
                        <span class="text-2xl text-gray-500 line-through">₹{{ number_format($product->price, 0) }}</span>
                        <span class="bg-primary-600 text-white text-sm font-bold px-2 py-1 rounded">
                            SAVE {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                        </span>
                    </div>
                @else
                    <span class="text-4xl font-bold text-gray-900">₹{{ number_format($product->price, 0) }}</span>
                @endif
            </div>

            <div class="prose max-w-none mb-6">
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>

            @if($product->variants->count() > 0)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Variant</label>
                    <select wire:model="selectedVariant" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                        <option value="">Default</option>
                        @foreach($product->variants as $variant)
                            <option value="{{ $variant->id }}">
                                {{ $variant->name }} - ₹{{ number_format($variant->price, 0) }}
                                @if($variant->stock_quantity > 0)
                                    ({{ $variant->stock_quantity }} in stock)
                                @else
                                    (Out of stock)
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if($product->stock_quantity > 0)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button wire:click="decrementQuantity"
                                    class="px-4 py-2 hover:bg-gray-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="px-6 py-2 font-semibold">{{ $quantity }}</span>
                            <button wire:click="incrementQuantity"
                                    class="px-4 py-2 hover:bg-gray-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mb-6">
                    <button wire:click="addToCart"
                            class="flex-1 bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Add to Cart
                    </button>
                    <button class="bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                </div>
            @else
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    This product is currently out of stock.
                </div>
            @endif

            <div class="border-t pt-6">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">SKU:</span>
                        <span class="font-medium ml-2">{{ $product->sku }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Category:</span>
                        <a href="{{ route('categories.show', $product->category->slug) }}" class="font-medium ml-2 text-blue-600 hover:text-blue-700" wire:navigate>
                            {{ $product->category->name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Tabs -->
    <div class="bg-white rounded-lg shadow-sm" x-data="{ activeTab: 'description' }">
        <div class="border-b">
            <nav class="flex gap-8 px-6">
                <button @click="activeTab = 'description'"
                        :class="activeTab === 'description' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600'"
                        class="py-4 border-b-2 font-medium transition">
                    Description
                </button>
                <button @click="activeTab = 'specifications'"
                        :class="activeTab === 'specifications' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600'"
                        class="py-4 border-b-2 font-medium transition">
                    Specifications
                </button>
                <button @click="activeTab = 'reviews'"
                        :class="activeTab === 'reviews' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600'"
                        class="py-4 border-b-2 font-medium transition">
                    Reviews ({{ $reviews->total() }})
                </button>
            </nav>
        </div>

        <div class="p-6">
            <div x-show="activeTab === 'description'" class="prose max-w-none">
                <p>{{ $product->description }}</p>
            </div>

            <div x-show="activeTab === 'specifications'">
                <table class="w-full">
                    <tbody>
                        <tr class="border-b">
                            <td class="py-3 text-gray-600 font-medium">SKU</td>
                            <td class="py-3">{{ $product->sku }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 text-gray-600 font-medium">Category</td>
                            <td class="py-3">{{ $product->category->name }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-3 text-gray-600 font-medium">Stock Status</td>
                            <td class="py-3">
                                @if($product->stock_quantity > 0)
                                    <span class="text-green-600">In Stock</span>
                                @else
                                    <span class="text-red-600">Out of Stock</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div x-show="activeTab === 'reviews'">
                @forelse($reviews as $review)
                    <div class="border-b pb-4 mb-4 last:border-b-0">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <div class="font-semibold">{{ $review->customer->name ?? 'Anonymous' }}</div>
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                        @if($review->title)
                            <h4 class="font-semibold mb-1">{{ $review->title }}</h4>
                        @endif
                        <p class="text-gray-700">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No reviews yet. Be the first to review this product!</p>
                @endforelse
                
                <div class="mt-4">
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @livewire('related-products', ['productId' => $product->id, 'categoryId' => $product->category_id], key('related-products-'.$product->id))
</div>
