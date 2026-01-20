@props([
    'file' => null,
    'actions' => [],
])

@php
    if (is_array($actions)) {
        $actions = array_filter(
            $actions,
            fn ($action): bool => $action->isVisible(),
        );
    }
@endphp

@if ($file)
    <div class="block w-full">
        <div class="relative w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-100 dark:border-gray-700 dark:bg-gray-900 shadow-sm group">
            
            <div class="aspect-video w-full flex items-center justify-center overflow-hidden bg-checkered">
                @if (str($file['type'])->contains('image'))
                    <img
                        src="{{ $file['url'] }}"
                        alt="{{ $file['alt'] ?? '' }}"
                        class="h-full w-full object-contain"
                        loading="lazy"
                    />
                @elseif (str($file['type'])->contains('video'))
                    <video controls src="{{ $file['url'] }}" class="h-full w-full"></video>
                @else
                    <div class="p-8">
                         <x-filament::icon
                            icon="heroicon-o-document-text"
                            class="w-16 h-16 text-gray-400"
                        />
                         <p class="mt-2 text-center text-xs font-bold uppercase text-gray-500">{{ $file['ext'] }}</p>
                    </div>
                @endif
            </div>

            <!-- Actions Overlay -->
            @if (count($actions))
                <div class="absolute top-2 right-2 flex items-center gap-1 z-10 transition-opacity duration-200">
                    <div class="flex items-center gap-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-1">
                        @foreach ($actions as $action)
                            <div class="inline-flex">
                                {{ ($action)(['item' => $file]) }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
             <!-- Bottom Info Bar -->
            <div class="flex items-center justify-between px-3 py-2 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                <span>{{ $file['width'] }} &times; {{ $file['height'] }} px</span>
                <span>{{ $file['size_for_humans'] }}</span>
            </div>
        </div>
    </div>
@endif
