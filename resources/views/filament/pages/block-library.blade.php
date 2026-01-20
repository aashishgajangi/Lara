<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        {{-- Hero Block --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col hover:shadow-md transition duration-300 overflow-hidden group">
            <div class="h-40 bg-gray-50 dark:bg-gray-800 flex items-center justify-center border-b border-gray-100 dark:border-gray-700 relative overflow-hidden">
                <!-- SVG Wireframe for Hero -->
                <svg class="w-full h-full text-gray-300 dark:text-gray-600" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="200" fill="#F3F4F6"/>
                    <!-- Background Image Placeholder -->
                    <path d="M0 0H400V200H0V0Z" fill="currentcolor" fill-opacity="0.1"/>
                    <path d="M117 76L176 135L260 51L344 135V200H117V76Z" fill="currentColor" fill-opacity="0.1"/>
                    <!-- Overlay -->
                    <rect width="400" height="200" fill="#1F2937" fill-opacity="0.2"/>
                    <!-- Content -->
                    <rect x="100" y="70" width="200" height="20" rx="4" fill="white"/>
                    <rect x="130" y="100" width="140" height="10" rx="4" fill="white" fill-opacity="0.8"/>
                    <rect x="160" y="130" width="80" height="24" rx="4" fill="#3B82F6"/>
                </svg>
                <div class="absolute inset-0 bg-blue-500 bg-opacity-0 group-hover:bg-opacity-5 transition duration-300"></div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg text-blue-600 dark:text-blue-400">
                        <x-heroicon-m-star class="w-6 h-6"/>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hero Banner</h3>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    A high-impact header used at the top of pages. Supports a large headline, subtitle, Call-to-Action button, and responsive background images.
                </p>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col hover:shadow-md transition duration-300 overflow-hidden group">
            <div class="h-40 bg-gray-50 dark:bg-gray-800 flex items-center justify-center border-b border-gray-100 dark:border-gray-700 relative">
                <!-- SVG Wireframe for Product Grid -->
                <svg class="w-full h-full text-gray-300 dark:text-gray-600" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="200" fill="#F9FAFB"/>
                    <!-- Product Cards -->
                    <g transform="translate(40, 40)">
                        <!-- Card 1 -->
                        <rect x="0" y="0" width="90" height="120" rx="4" fill="white" stroke="currentColor" stroke-width="2"/>
                        <rect x="10" y="10" width="70" height="60" rx="2" fill="currentColor" fill-opacity="0.2"/>
                        <rect x="10" y="80" width="50" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                        <rect x="10" y="95" width="30" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                    </g>
                    <g transform="translate(155, 40)">
                        <!-- Card 2 -->
                        <rect x="0" y="0" width="90" height="120" rx="4" fill="white" stroke="currentColor" stroke-width="2"/>
                        <rect x="10" y="10" width="70" height="60" rx="2" fill="currentColor" fill-opacity="0.2"/>
                        <rect x="10" y="80" width="50" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                        <rect x="10" y="95" width="30" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                    </g>
                    <g transform="translate(270, 40)">
                        <!-- Card 3 (Cutoff) -->
                        <rect x="0" y="0" width="90" height="120" rx="4" fill="white" stroke="currentColor" stroke-width="2"/>
                        <rect x="10" y="10" width="70" height="60" rx="2" fill="currentColor" fill-opacity="0.2"/>
                    </g>
                </svg>
                <div class="absolute inset-0 bg-purple-500 bg-opacity-0 group-hover:bg-opacity-5 transition duration-300"></div>
            </div>
            <div class="p-6">
                 <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg text-purple-600 dark:text-purple-400">
                        <x-heroicon-m-shopping-bag class="w-6 h-6"/>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Product Grid</h3>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Dynamically display products. Choose between "Featured", "Latest", or "On Sale" views. Configurable layout and product count.
                </p>
            </div>
        </div>

        {{-- Text Block --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col hover:shadow-md transition duration-300 overflow-hidden group">
            <div class="h-40 bg-gray-50 dark:bg-gray-800 flex items-center justify-center border-b border-gray-100 dark:border-gray-700 relative">
                <!-- SVG Wireframe for Text -->
                <svg class="w-full h-full text-gray-300 dark:text-gray-600" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="200" fill="white"/>
                    <!-- Lines of text -->
                    <rect x="40" y="40" width="200" height="16" rx="2" fill="currentColor" fill-opacity="0.8"/>
                    <rect x="40" y="80" width="320" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                    <rect x="40" y="100" width="300" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                    <rect x="40" y="120" width="310" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                    <rect x="40" y="140" width="180" height="8" rx="2" fill="currentColor" fill-opacity="0.4"/>
                </svg>
                <div class="absolute inset-0 bg-gray-500 bg-opacity-0 group-hover:bg-opacity-5 transition duration-300"></div>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-600 dark:text-gray-300">
                        <x-heroicon-m-document-text class="w-6 h-6"/>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Rich Text</h3>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    A versatile text block. Use it for article content, descriptions, or any formatted text. Supports headings, bold/italic, lists, and links.
                </p>
            </div>
        </div>

        {{-- Single Image --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col hover:shadow-md transition duration-300 overflow-hidden group">
            <div class="h-40 bg-gray-50 dark:bg-gray-800 flex items-center justify-center border-b border-gray-100 dark:border-gray-700 relative">
                <!-- SVG Wireframe for Image -->
                <svg class="w-full h-full text-gray-300 dark:text-gray-600" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="200" fill="#F3F4F6"/>
                    <rect x="80" y="20" width="240" height="160" rx="4" fill="white" stroke="currentColor" stroke-width="2"/>
                    <path d="M120 100L160 140L200 80L280 160H120V100Z" fill="currentColor" fill-opacity="0.2"/>
                    <circle cx="260" cy="60" r="15" fill="currentColor" fill-opacity="0.2"/>
                </svg>
                <div class="absolute inset-0 bg-green-500 bg-opacity-0 group-hover:bg-opacity-5 transition duration-300"></div>
            </div>
            <div class="p-6">
                 <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
                        <x-heroicon-m-photo class="w-6 h-6"/>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Single Image</h3>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Embed a large, high-quality image with optimized loading. Includes support for Alt Text, Captions, and optional click-through links.
                </p>
            </div>
        </div>
        
        {{-- Custom HTML --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 flex flex-col hover:shadow-md transition duration-300 overflow-hidden group">
            <div class="h-40 bg-gray-900 flex items-center justify-center border-b border-gray-100 dark:border-gray-700 relative">
                <!-- SVG Wireframe for Code -->
                <svg class="w-full h-full text-green-400" viewBox="0 0 400 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="400" height="200" fill="#111827"/>
                    <text x="40" y="60" font-family="monospace" font-size="14" fill="#60A5FA">&lt;div class="custom"&gt;</text>
                    <text x="60" y="90" font-family="monospace" font-size="14" fill="#9CA3AF">  &lt;!-- Content --&gt;</text>
                    <text x="40" y="120" font-family="monospace" font-size="14" fill="#60A5FA">&lt;/div&gt;</text>
                </svg>
                <div class="absolute inset-0 bg-white bg-opacity-0 group-hover:bg-opacity-5 transition duration-300"></div>
            </div>
            <div class="p-6">
                 <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-red-50 dark:bg-red-900/30 rounded-lg text-red-600 dark:text-red-400">
                         <x-heroicon-m-code-bracket class="w-6 h-6"/>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Custom HTML</h3>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    For developers. Insert raw HTML, scripts, or embeds (e.g., Google Maps, YouTube). Use with caution as it allows full code execution.
                </p>
            </div>
        </div>

    </div>
</x-filament-panels::page>
