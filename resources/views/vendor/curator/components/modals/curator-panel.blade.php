@php
    $selectedIds = collect($this->selected)->pluck('id')->all();
@endphp

<div class="flex flex-col h-[85vh] bg-gray-50 dark:bg-gray-900 overflow-hidden -m-6 rounded-xl">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex-shrink-0 z-20">
        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
            Media Library
        </h2>

        <div class="flex items-center gap-4 w-full max-w-md">
            <div class="relative w-full">
                <x-filament::icon
                    icon="heroicon-m-magnifying-glass"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition"
                />
                <input
                    type="search"
                    placeholder="{{ trans('curator::views.panel.search_placeholder') }}"
                    wire:model.live.debounce.500ms="search"
                    class="w-full pl-10 pr-4 py-2 text-sm bg-gray-100 dark:bg-gray-700 border-none rounded-lg focus:ring-2 focus:ring-primary-500 placeholder-gray-500 dark:text-white transition"
                />
            </div>
            
             <div wire:loading wire:target="search">
                <x-filament::loading-indicator class="h-5 w-5 text-gray-500" />
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-1 overflow-hidden">
        
        <!-- Gallery Section -->
        <div class="flex-1 overflow-y-auto p-6">
            @if (count($files) > 0)
                <ul class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                    @foreach ($files as $file)
                        @php
                            $isSelected = in_array($file['id'], $selectedIds);
                        @endphp
                        <li
                            wire:key="media-{{ $file['id'] }}"
                            class="relative aspect-square group cursor-pointer"
                            wire:click="@if($isSelected) removeFromSelection({{ json_encode($file['id']) }}) @else addToSelection({{ json_encode($file['id']) }}) @endif"
                        >
                            <div
                                @class([
                                    'w-full h-full rounded-xl overflow-hidden border-2 transition-all duration-200 shadow-sm hover:shadow-md relative bg-gray-200 dark:bg-gray-700',
                                    'border-primary-500 ring-2 ring-primary-500/20 z-10 scale-[0.98]' => $isSelected,
                                    'border-transparent hover:border-gray-300 dark:hover:border-gray-600' => ! $isSelected,
                                    'opacity-50' => count($selectedIds) > 0 && ! $isSelected,
                                ])
                            >
                                @if (str_contains($file['type'], 'image'))
                                    <img
                                        src="{{ $file['url'] }}"
                                        alt="{{ $file['alt'] ?? '' }}"
                                        loading="lazy"
                                        class="w-full h-full object-cover"
                                    />
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-2 text-center">
                                       <x-filament::icon
                                            icon="heroicon-o-document"
                                            class="w-8 h-8 mb-2"
                                        />
                                        <span class="text-[10px] uppercase font-bold tracking-wider">{{ $file['ext'] }}</span>
                                    </div>
                                @endif
                                
                                <!-- Selection Badge -->
                                @if ($isSelected)
                                    <div
                                        class="absolute top-2 right-2 bg-primary-500 text-white rounded-full p-1 shadow-md z-20"
                                    >
                                        <x-filament::icon icon="heroicon-m-check" class="w-3 h-3" />
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-1.5 px-0.5">
                                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate">{{ $file['pretty_name'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                
                @if ($this->currentPage < $this->lastPage)
                    <div class="mt-8 flex justify-center pb-4">
                        <x-filament::button
                            size="md"
                            color="gray"
                            wire:click="loadMoreFiles"
                            wire:loading.attr="disabled"
                        >
                            {{ trans('curator::views.panel.load_more') }}
                        </x-filament::button>
                    </div>
                @endif
                 
            @else
                <div class="flex flex-col items-center justify-center h-full text-center p-8">
                     <div class="p-6 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                        <x-filament::icon
                            icon="heroicon-o-photo"
                            class="w-12 h-12 text-gray-400"
                        />
                     </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No media found</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Upload new media to get started.</p>
                </div>
            @endif
        </div>

        <!-- Right Sidebar (Context & Actions) -->
        <div class="h-full w-80 border-l border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 flex flex-col flex-shrink-0 shadow-[0_0_15px_rgba(0,0,0,0.05)] z-20 overflow-hidden">
            <!-- Sidebar Header -->
             <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="font-bold text-gray-950 dark:text-white">
                    @if (count($selected) === 1)
                        Media Details
                    @elseif (count($selected) > 1)
                        {{ count($selected) }} items selected
                    @else
                        Actions
                    @endif
                </h3>
            </div>
            
            <!-- Sidebar Content -->
            <div class="flex-1 overflow-y-auto p-6 scroll-thin min-h-0">
                @if (count($selected) === 1)
                     <!-- Edit Single Mode -->
                    <div class="space-y-4">
                        <!-- Form renders the Preview (edit-preview.blade.php) and inputs -->
                        <div class="space-y-4">
                            {{ $this->form }}
                        </div>
                        
                        <!-- Secondary Actions -->
                        @if ($this->updateFileAction->isVisible())
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                {{ $this->updateFileAction }}
                            </div>
                        @endif
                    </div>
                @elseif (count($selected) > 1)
                    <!-- Multiple Select Mode -->
                    <div class="flex flex-col justify-center items-center h-40 text-center space-y-2">
                        <div class="p-4 bg-primary-50 dark:bg-primary-900/20 rounded-full text-primary-600">
                            <x-filament::icon icon="heroicon-m-rectangle-stack" class="w-8 h-8" />
                        </div>
                        <p class="text-sm text-gray-500">Manage multiple selection from here.</p>
                         <x-filament::button
                            color="gray"
                            outline
                            wire:click="set('selected', [])"
                            size="sm"
                        >
                            Clear Selection
                        </x-filament::button>
                    </div>
                @else
                    <!-- Default / Upload Mode -->
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500">Upload new files to your library.</p>
                         @if ($this->addFilesAction->isVisible())
                            {{ $this->addFilesAction }}
                        @endif
                    </div>
                @endif
            </div>

            <!-- Sidebar Footer Actions -->
             <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex flex-col gap-2">
                    @if (count($selected) === 1)
                        <!-- Single Selection Actions -->
                        {{ $this->insertMediaAction }}
                        <x-filament::button
                            color="gray"
                            outlined
                            wire:click="set('selected', [])"
                            class="w-full"
                        >
                            Unselect
                        </x-filament::button>
                    @elseif (count($selected) > 1)
                        <!-- Multiple Selection Actions -->
                        {{ $this->insertMediaAction }}
                    @else
                        <!-- No Selection Actions -->
                        {{ $this->addInsertFilesAction }}
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
