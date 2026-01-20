<div>
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

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Cart Items -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b flex items-center justify-between">
                        <h2 class="text-lg font-bold">Shopping Cart ({{ $cartItems->count() }} items)</h2>
                        <button wire:click="clearCart" 
                                wire:confirm="Are you sure you want to clear your cart?"
                                class="text-red-600 text-sm hover:text-red-700">
                            Clear Cart
                        </button>
                    </div>
                    
                    @foreach($cartItems as $item)
                        <div class="flex flex-row items-start sm:items-center gap-4 p-4 border-b last:border-b-0">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="flex-shrink-0 w-24 sm:w-auto" wire:navigate>
                                @if($item->product->primary_image)
                                    <img src="{{ $item->product->primary_image->src }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-24 h-24 object-cover rounded hover:opacity-75 transition">
                                @else
                                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                                        <span class="text-gray-400 text-xs">No image</span>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="flex-1 min-w-0 w-full">
                                <a href="{{ route('products.show', $item->product->slug) }}" 
                                   class="font-semibold text-gray-900 hover:text-blue-600 block" wire:navigate>
                                    {{ $item->product->name }}
                                </a>
                                <p class="text-sm text-gray-500">{{ $item->product->category->name ?? 'Uncategorized' }}</p>
                                <p class="text-blue-600 font-semibold mt-1">₹{{ number_format($item->product->getEffectivePrice(), 0) }} each</p>
                                
                                @if($item->product->stock_quantity < 10)
                                    <p class="text-orange-600 text-xs mt-1">Only {{ $item->product->stock_quantity }} left in stock!</p>
                                @endif
                                
                                <!-- Mobile quantity controls -->
                                <div class="flex items-center justify-between sm:hidden mt-4">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="decrementQuantity({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition disabled:opacity-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        <span class="w-16 text-center font-semibold text-lg">{{ $item->quantity }}</span>
                                        <button wire:click="incrementQuantity({{ $item->id }})"
                                                wire:loading.attr="disabled"
                                                class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition disabled:opacity-50">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900 text-lg">₹{{ number_format($item->product->getEffectivePrice() * $item->quantity, 0) }}</p>
                                    </div>
                                </div>
                                
                                <!-- Mobile remove button -->
                                <button wire:click="removeItem({{ $item->id }})"
                                        class="sm:hidden text-red-600 text-sm hover:text-red-700 mt-3 flex items-center gap-1 w-full justify-center py-2 border border-red-200 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Remove from Cart
                                </button>
                            </div>
                            
                            <!-- Desktop quantity controls -->
                            <div class="hidden sm:flex items-center gap-2">
                                <button wire:click="decrementQuantity({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition disabled:opacity-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="w-16 text-center font-semibold">{{ $item->quantity }}</span>
                                <button wire:click="incrementQuantity({{ $item->id }})"
                                        wire:loading.attr="disabled"
                                        class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded hover:bg-gray-100 transition disabled:opacity-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Desktop price and remove -->
                            <div class="hidden sm:block text-right">
                                <p class="font-bold text-gray-900">₹{{ number_format($item->product->getEffectivePrice() * $item->quantity, 0) }}</p>
                                <button wire:click="removeItem({{ $item->id }})"
                                        class="text-red-600 text-sm hover:text-red-700 mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Coupon Code -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-bold mb-4">Have a Coupon Code?</h3>
                    
                    @if($appliedCoupon)
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-green-800">Coupon Applied: {{ $appliedCoupon->code }}</p>
                                <p class="text-sm text-green-600">
                                    You're saving ₹{{ number_format($discount, 0) }}!
                                </p>
                            </div>
                            <button wire:click="removeCoupon" class="text-red-600 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <input type="text" 
                                   wire:model="couponCode"
                                   placeholder="Enter coupon code"
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button wire:click="applyCoupon"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                                Apply
                            </button>
                        </div>
                        @if($couponError)
                            <p class="text-red-600 text-sm mt-2">{{ $couponError }}</p>
                        @endif
                    @endif
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4 space-y-6">
                    <h2 class="text-xl font-bold">Order Summary</h2>
                    
                    <!-- Shipping Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Method</label>
                        <select wire:model.live="shippingMethod" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($shippingRates as $key => $rate)
                                <option value="{{ $key }}">
                                    {{ $rate['name'] }} - ₹{{ number_format($rate['cost'], 0) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="space-y-2 pt-4 border-t">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span class="font-semibold">₹{{ number_format($subtotal, 0) }}</span>
                        </div>
                        
                        @if($discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Discount</span>
                                <span class="font-semibold">-₹{{ number_format($discount, 0) }}</span>
                            </div>
                        @endif
                        
                        <div class="flex justify-between text-gray-600">
                            <span>Shipping</span>
                            <span class="font-semibold">
                                @if($shipping > 0)
                                    ₹{{ number_format($shipping, 0) }}
                                @else
                                    <span class="text-green-600">FREE</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="flex justify-between text-gray-600">
                            <span>Tax (10%)</span>
                            <span class="font-semibold">₹{{ number_format($tax, 0) }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span class="text-blue-600">₹{{ number_format($total, 0) }}</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('checkout') }}" 
                       class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition" wire:navigate>
                        Proceed to Checkout
                    </a>
                    
                    <a href="{{ route('products.index') }}" 
                       class="block w-full text-center py-3 text-blue-600 hover:text-blue-700 border border-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition" wire:navigate>
                        Continue Shopping
                    </a>
                    
                    <!-- Trust Badges -->
                    <div class="pt-4 border-t space-y-2 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <span>Secure Checkout</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span>Safe Payment</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Easy Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="text-6xl mb-4">🛒</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('products.index') }}" 
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition" wire:navigate>
                Start Shopping
            </a>
        </div>
    @endif
</div>
