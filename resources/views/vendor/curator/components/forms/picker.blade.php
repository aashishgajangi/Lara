@php
    $statePath = $getStatePath();
    $items = $getState() ?? [];
    $itemsCount = count($items);
    $isMultiple = $isMultiple();
    $maxItems = $getMaxItems();
    $shouldDisplayAsList = $shouldDisplayAsList();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            insertMedia: function (event) {
                if (event.detail.statePath !== '{{ $statePath }}') return;
                $wire.$set(event.detail.statePath, event.detail.media);
            },
        }"
        x-on:insert-content.window="insertMedia($event)"
        class="curator-media-picker w-full"
    >
        @if ($itemsCount > 0)
            <ul
                x-sortable
                wire:end.stop="mountFormComponentAction('{{ $statePath }}', 'reorder', { items: $event.target.sortable.toArray() })"
                @class([
                    'gap-4',
                    'grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6' => ! $shouldDisplayAsList,
                    'flex flex-col gap-2' => $shouldDisplayAsList,
                ])
            >
                @foreach ($items as $uuid => $item)
                    <li
                        wire:key="{{ $this->getId() }}.{{ $uuid }}.{{ $field::class }}.item"
                        x-sortable-item="{{ $uuid }}"
                        @class([
                            'relative group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 transition ring-1 ring-transparent hover:ring-primary-500/50',
                            'aspect-square' => ! $shouldDisplayAsList,
                            'flex items-center p-2' => $shouldDisplayAsList,
                        ])
                    >
                        <!-- Image/Preview -->
                        @if (str($item['type'])->contains('image'))
                            <div @class(['w-full h-full relative overflow-hidden', 'w-12 h-12 rounded-lg flex-shrink-0' => $shouldDisplayAsList])>
                                <img
                                    src="{{ $item['thumbnail_url'] ?? $item['url'] }}"
                                    alt="{{ $item['alt'] ?? $item['name'] }}"
                                    @class([
                                        'w-full h-full object-cover transition duration-300 group-hover:scale-105',
                                    ])
                                    loading="lazy"
                                />
                            </div>
                        @else
                            <div @class(['w-full h-full flex items-center justify-center bg-gray-50 dark:bg-gray-900', 'w-12 h-12 rounded-lg flex-shrink-0' => $shouldDisplayAsList])>
                                <x-filament-actions::group
                                    :actions="[
                                        $getAction('view')(['url' => $item['url']]),
                                    ]"
                                    color="gray"
                                    size="md"
                                    class="opacity-50"
                                />
                                <span class="text-xs font-bold uppercase text-gray-400 absolute bottom-2 right-2">{{ $item['ext'] }}</span>
                            </div>
                        @endif

                        <!-- Gradient Overlay (Grid only) -->
                        @if (! $shouldDisplayAsList)
                            <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                        @endif

                        <!-- Metadata -->
                        <div @class([
                            'flex-1 min-w-0 px-3',
                            'absolute bottom-0 left-0 right-0 p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300' => ! $shouldDisplayAsList,
                        ])>
                            @if (! $shouldDisplayAsList)
                                <p class="text-xs font-medium text-white truncate drop-shadow-sm">{{ $item['pretty_name'] }}</p>
                                <p class="text-[10px] text-gray-300 truncate drop-shadow-sm">{{ $item['size_for_humans'] }}</p>
                            @else
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-950 dark:text-white truncate">{{ $item['pretty_name'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $item['size_for_humans'] }} . {{ $item['ext'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div @class([
                            'flex items-center gap-1',
                            'absolute top-2 right-2 z-10' => ! $shouldDisplayAsList,
                            'ml-auto' => $shouldDisplayAsList,
                        ])>
                            <div class="flex items-center gap-1 bg-white/90 dark:bg-gray-800/90 rounded-full p-1 shadow-sm backdrop-blur-sm">
                                @if ($isMultiple)
                                    <div
                                        x-sortable-handle
                                        class="cursor-move p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition"
                                    >
                                        <x-filament::icon
                                            icon="heroicon-m-arrows-pointing-out"
                                            class="w-4 h-4"
                                        />
                                    </div>
                                @endif

                                <x-filament-actions::group
                                    :actions="[
                                        $getAction('view')(['url' => $item['url']]),
                                        $getAction('edit')(['id' => $item['id']]),
                                        $getAction('download')(['uuid' => $uuid]),
                                        $getAction('remove')(['uuid' => $uuid]),
                                    ]"
                                    color="gray"
                                    size="xs"
                                    dropdown-placement="bottom-end"
                                />
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        @if (! $itemsCount || ($isMultiple && (! $maxItems || $itemsCount < $maxItems)))
            <div class="mt-4 flex justify-center">
               {{ $getAction('open_curator_picker') }}
            </div>
        @endif
        
        @if ($itemsCount > 0)
             <div class="mt-2 text-right">
                {{ $getAction('removeAll') }}
            </div>
        @endif
    </div>
</x-dynamic-component>