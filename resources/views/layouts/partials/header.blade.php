@php
    $headerPhone = \App\Models\SiteSetting::get('header_phone', '+1 (555) 123-4567');
    $headerEmail = \App\Models\SiteSetting::get('header_email', 'support@laracommerce.com');
    $siteLogoText = \App\Models\SiteSetting::get('site_logo_text', 'LaraCommerce');
    $siteLogoImage = \App\Models\SiteSetting::getMediaUrl('site_logo_image');
    $headerMenu = \App\Models\Menu::getByLocation('header');
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
    
    // Top Bar Settings
    $topBarEnabled = \App\Models\SiteSetting::get('top_bar_enabled', true);
    $topBarContent = \App\Models\SiteSetting::get('top_bar_content');
    $topBarBg = \App\Models\SiteSetting::get('top_bar_bg_color', '#111827');
    $topBarText = \App\Models\SiteSetting::get('top_bar_text_color', '#ffffff');
    $headerSticky = \App\Models\SiteSetting::get('header_sticky', true);
@endphp
<header class="bg-white shadow-sm {{ $headerSticky ? 'sticky top-0 z-50' : '' }}" x-data="{ mobileMenuOpen: false, searchOpen: false }">
    @if($topBarEnabled)
        <div class="py-2 bg-topbar-bg text-topbar-text">
            <div class="{{ $containerClass }}">
                @if($topBarContent)
                    <div class="prose prose-sm max-w-none prose-invert" style="color: inherit">
                        {!! $topBarContent !!}
                    </div>
                @else
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
                @endif
            </div>
        </div>
    @endif

    <div class="{{ $containerClass }} py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-900 flex items-center" wire:navigate>
                    @if($siteLogoImage)
                        <img src="{{ $siteLogoImage }}" alt="{{ $siteLogoText }}" class="h-8 w-auto mr-2">
                    @else
                        🛒 {{ $siteLogoText }}
                    @endif
                </a>

                <nav class="hidden md:flex space-x-8">
                    @if($headerMenu && $headerMenu->items->count() > 0)
                        @foreach($headerMenu->items as $item)
                            @if($item->children->count() > 0)
                                <div class="relative group">
                                    <button class="text-gray-700 hover:text-primary-600 transition flex items-center">
                                        {{ $item->title }}
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        @foreach($item->children as $child)
                                            <a href="{{ $child->url }}" 
                                               target="{{ $child->target }}"
                                               @if(!$child->target) wire:navigate @endif
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                {{ $child->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $item->url }}" 
                                   target="{{ $item->target }}"
                                   @if(!$item->target) wire:navigate @endif
                                   class="text-gray-700 hover:text-primary-600 transition {{ $item->css_class }}">
                                    @if($item->icon)
                                        <i class="{{ $item->icon }} mr-1"></i>
                                    @endif
                                    {{ $item->title }}
                                </a>
                            @endif
                        @endforeach
                    @else
                        <a href="/" class="text-gray-700 hover:text-primary-600 transition" wire:navigate>Home</a>
                        <a href="/products" class="text-gray-700 hover:text-primary-600 transition" wire:navigate>Products</a>
                        <a href="/categories" class="text-gray-700 hover:text-primary-600 transition" wire:navigate>Categories</a>
                        <a href="/about" class="text-gray-700 hover:text-primary-600 transition" wire:navigate>About</a>
                        <a href="/contact" class="text-gray-700 hover:text-primary-600 transition" wire:navigate>Contact</a>
                    @endif
                </nav>
            </div>

            <div class="flex items-center space-x-4">
                <button @click="searchOpen = !searchOpen" class="text-gray-700 hover:text-primary-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700 hover:text-primary-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" wire:navigate>Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-primary-600 font-medium" wire:navigate>Login</a>
                @endauth

                @livewire('cart-icon')

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700">
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
             x-cloak
             class="mt-4">
            @livewire('product-search')
        </div>
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-cloak
         class="md:hidden bg-white border-t">
        <nav class="container mx-auto px-4 py-4 space-y-2">
            @if($headerMenu && $headerMenu->items->count() > 0)
                @foreach($headerMenu->items as $item)
                    <a href="{{ $item->url }}" class="block py-2 text-gray-700 hover:text-primary-600 font-medium" wire:navigate>{{ $item->title }}</a>
                    @if($item->children->count() > 0)
                        @foreach($item->children as $child)
                            <a href="{{ $child->url }}" class="block py-2 pl-4 text-sm text-gray-600 hover:text-primary-600" wire:navigate>{{ $child->title }}</a>
                        @endforeach
                    @endif
                @endforeach
            @else
                <a href="/" class="block py-2 text-gray-700 hover:text-primary-600 font-medium" wire:navigate>Home</a>
                <a href="/products" class="block py-2 text-gray-700 hover:text-primary-600 font-medium" wire:navigate>Products</a>
                <a href="/categories" class="block py-2 text-gray-700 hover:text-primary-600 font-medium" wire:navigate>Categories</a>
                <a href="/about" class="block py-2 text-gray-700 hover:text-primary-600 font-medium" wire:navigate>About</a>
                <a href="/contact" class="block py-2 text-gray-700 hover:text-primary-600 font-medium" wire:navigate>Contact</a>
            @endif
        </nav>
    </div>
</header>
