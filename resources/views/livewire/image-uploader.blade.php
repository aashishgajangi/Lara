<div class="w-full">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    
    @if (session()->has('image-success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('image-success') }}
        </div>
    @endif

    @if (session()->has('image-error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('image-error') }}
        </div>
    @endif

    @if (!$uploadedImagePath)
        <div 
            class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-gray-400 transition-colors cursor-pointer"
            ondrop="handleDrop(event)" 
            ondragover="handleDragOver(event)" 
            ondragenter="handleDragEnter(event)" 
            ondragleave="handleDragLeave(event)"
            onclick="document.getElementById('file-input-{{ $imageType }}').click()"
        >
            <div class="space-y-4">
                <div class="mx-auto w-16 h-16 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-lg text-gray-600">
                        <span class="font-medium">Click to upload</span> or drag and drop
                    </p>
                    <p class="text-sm text-gray-500">PNG, JPG, WebP up to 2MB</p>
                </div>
            </div>
            
            <input 
                type="file" 
                id="file-input-{{ $imageType }}"
                wire:model="image" 
                accept="image/*"
                class="hidden"
            >
        </div>
        
        @if ($isUploading)
            <div class="mt-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <span>Uploading...</span>
                    <span>{{ $uploadProgress }}%</span>
                </div>
                <div class="mt-2 bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $uploadProgress }}%"></div>
                </div>
            </div>
        @endif
    @else
        <div class="space-y-4">
            <div class="relative">
                <img 
                    src="{{ Storage::disk('public')->url($uploadedImagePath) }}" 
                    alt="Uploaded image" 
                    class="w-full max-w-sm h-48 object-cover rounded-lg border"
                >
                <button 
                    wire:click="removeImage"
                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                    title="Remove image"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-sm text-gray-600">
                    <strong>Path:</strong> {{ $uploadedImagePath }}
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Copy this path to use in your form
                </p>
            </div>
            
            <button 
                wire:click="$set('uploadedImagePath', null)"
                class="text-blue-600 hover:text-blue-800 text-sm font-medium"
            >
                Upload another image
            </button>
        </div>
    @endif

    @error('image')
        <div class="mt-2 text-red-600 text-sm">{{ $message }}</div>
    @enderror
</div>

<script>
    function handleDragOver(e) {
        e.preventDefault();
        e.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
    }
    
    function handleDragEnter(e) {
        e.preventDefault();
        e.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
    }
    
    function handleDragLeave(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
    }
    
    function handleDrop(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const fileInput = e.currentTarget.querySelector('input[type="file"]');
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
</script>
