<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    @foreach($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}" 
           class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow p-6 text-center group">
            @if($category->image)
                <div class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden bg-gray-100">
                    <img src="{{ Storage::disk('public')->url($category->image) }}" 
                         alt="{{ $category->name }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
            @else
                <div class="w-20 h-20 mx-auto mb-3 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-2xl">📦</span>
                </div>
            @endif
            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition">
                {{ $category->name }}
            </h3>
            <p class="text-xs text-gray-500 mt-1">{{ $category->products_count }} products</p>
        </a>
    @endforeach
</div>
{{-- Close your eyes. Count to one. That is how long forever feels. --}}
