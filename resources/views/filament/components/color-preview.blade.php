<div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
    <div 
        class="p-6 text-center font-semibold text-lg transition-all"
        style="background-color: {{ $bg }}; color: {{ $text }};"
    >
        {{ $label }}
    </div>
    <div class="bg-gray-50 dark:bg-gray-800 p-3 text-xs text-gray-600 dark:text-gray-400">
        <div class="flex justify-between items-center">
            <span>Background: <code class="bg-white dark:bg-gray-900 px-2 py-1 rounded">{{ $bg }}</code></span>
            <span>Text: <code class="bg-white dark:bg-gray-900 px-2 py-1 rounded">{{ $text }}</code></span>
        </div>
    </div>
</div>
