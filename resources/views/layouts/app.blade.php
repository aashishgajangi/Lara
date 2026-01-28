@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
    $colors = \App\Services\ColorService::getColors();
    $topBarBg = \App\Models\SiteSetting::get('top_bar_bg_color', '#111827');
    $topBarText = \App\Models\SiteSetting::get('top_bar_text_color', '#ffffff');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if($favicon = \App\Models\SiteSetting::getMediaUrl('site_favicon'))
        <link rel="icon" href="{{ $favicon }}">
    @endif

    {!! SEO::generate() !!}
    {!! JsonLd::generate() !!}

    @stack('meta')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            {!! \App\Services\ColorService::getCssVariables() !!}
        }
        
        [x-cloak] { display: none !important; }
        
        [x-cloak] { display: none !important; }
        
        /* Prevent horizontal scroll on mobile */
        /* html, body {
            max-width: 100%;
            overflow-x: hidden;
        } */
        
        /* Ensure all containers respect viewport width */
        /* * {
            max-width: 100%;
        } */
        
        /* Fix for images that might overflow */
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
    
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        @include('layouts.partials.header')

        <main class="flex-grow">
            @yield('content')
        </main>

        @include('layouts.partials.footer')
    </div>

    @livewireScripts
    @stack('scripts')
    <x-whatsapp-widget />
</body>
</html>
