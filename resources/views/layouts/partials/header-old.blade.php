<header class="bg-white shadow-sm sticky top-0 z-50" x-data="{ mobileMenuOpen: false, searchOpen: false }">
@php
    $headerPhone = \App\Models\SiteSetting::get('header_phone', '+1 (555) 123-4567');
    $headerEmail = \App\Models\SiteSetting::get('header_email', 'support@laracommerce.com');
@endphp
    <div class="bg-gray-900 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    <span>📞 Support: {{ $headerPhone }}</span>
                    <span>📧 {{ $headerEmail }}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#" class="hover:text-gray-300">Track Order</a>
                    <a href="#" class="hover:text-gray-300">Help</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900 flex items-center">
                    @if($siteLogoImage)
                        <img src="{{ Storage::url($siteLogoImage) }}" alt="{{ $siteLogoText }}" class="h-8 w-auto mr-2">
                    @else
                        🛒 {{ $siteLogoText }}
                    @endif
                </a>

                <nav class="hidden md:flex space-x-8">
                @if($headerMenu && $headerMenu->items->count() > 0)
                    @foreach($headerMenu->items as $item)
                        @if($item->children->count() > 0)
                            <div class="relative group">
                                <button class="text-gray-700 hover:text-blue-600 transition flex items-center">
                                    {{ $item->title }}
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                    @foreach($item->children as $child)
                                        <a href="{{ $child->url }}" 
                                           target="{{ $child->target }}"
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            {{ $child->title }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item->url }}" 
                               target="{{ $item->target }}"
                               class="text-gray-700 hover:text-blue-600 transition {{ $item->css_class }}">
                                @if($item->icon)
                                    <i class="{{ $item->icon }} mr-1"></i>
                                @endif
                                {{ $item->title }}
                            </a>
                        @endif
                    @endforeach
                @else
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition">Home</a>
                    <a href="/products" class="text-gray-700 hover:text-blue-600 transition">Products</a>
                    <a href="/categories" class="text-gray-700 hover:text-blue-600 transition">Categories</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 transition">About</a>
                    <a href="/contact" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
                @endif
            </nav>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">All Products</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Deals</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Contact</a>
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <button @click="searchOpen = !searchOpen" class="text-gray-700 hover:text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium">Login</a>
                @endauth

                @livewire('cart-icon')

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="searchOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="mt-4">
            @livewire('product-search')
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="lg:hidden bg-white border-t">
        <nav class="container mx-auto px-4 py-4 space-y-2">
            <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Home</a>
            <a href="{{ route('products.index') }}" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">All Products</a>
            <a href="#" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Deals</a>
            <a href="#" class="block py-2 text-gray-700 hover:text-blue-600 font-medium">Contact</a>
        </nav>
    </div>
</header>
