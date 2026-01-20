<div class="py-2">
    @forelse($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}" 
           class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition">
            {{ $category->name }}
            @if($category->children->count() > 0)
                <span class="text-xs text-gray-500">({{ $category->children->count() }})</span>
            @endif
        </a>
    @empty
        <div class="px-4 py-2 text-gray-500 text-sm">No categories available</div>
    @endforelse
</div>
