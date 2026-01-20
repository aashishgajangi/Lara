<div
    {{ $attributes->merge($getExtraAttributes())->class(['rounded-t-xl overflow-hidden']) }}
>
    @php
        $record = $getRecord();
    @endphp

    <div class="rounded-t-xl overflow-hidden bg-gray-100 dark:bg-gray-950/50">
        @if (str($record->type)->contains('image'))
            <img
                src="{{ $record->url }}"
                alt="{{ $record->alt }}"
                @class([
                    'w-full h-auto',
                    'mx-auto' => str($record->type)->contains('svg'),
                    'object-contain' => ! str($record->type)->contains('svg'),
                ])
            />
        @else
            <x-curator::document-image
                :label="$record->name"
                icon-size="lg"
                :type="$record->type"
                :extension="$record->ext"
            />
        @endif
        <div
            class="absolute inset-x-0 bottom-0 flex items-center justify-between px-1.5 pt-10 pb-1.5 text-xs text-white bg-gradient-to-t from-black/80 to-transparent gap-3"
        >
            <p class="truncate">{{ $record->pretty_name }}</p>
            <p class="flex-shrink-0">{{ $record->size_for_humans }}</p>
        </div>
    </div>
</div>
