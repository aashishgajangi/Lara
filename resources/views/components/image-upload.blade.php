@php
    $fieldName = $getStatePath();
    $currentValue = $getState() ?? '';
@endphp

<div x-data="imageUpload({
    name: '{{ $fieldName }}',
    type: '{{ $type ?? 'general' }}',
    customName: '{{ $customName ?? 'image' }}',
    initialValue: '{{ $currentValue }}'
})" 
x-init="init()"
class="space-y-4">

    <label class="block text-sm font-medium text-gray-700">{{ $label ?? 'Image Upload' }}</label>

    <!-- Upload Area -->
    <div x-show="!uploadedPath" 
         class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors cursor-pointer"
         :class="{ 'border-blue-400 bg-blue-50': isDragging }"
         @drop.prevent="handleDrop($event)" 
         @dragover.prevent="isDragging = true" 
         @dragenter.prevent="isDragging = true" 
         @dragleave.prevent="isDragging = false"
         @click="$refs.fileInput.click()">
        
        <div class="space-y-4">
            <div class="mx-auto w-12 h-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Click to upload</span> or drag and drop
                </p>
                <p class="text-xs text-gray-500">PNG, JPG, WebP up to 2MB</p>
            </div>
        </div>
        
        <input type="file" 
               x-ref="fileInput" 
               @change="handleFile($event.target.files[0])"
               accept="image/*" 
               class="hidden">
    </div>

    <!-- Upload Progress -->
    <div x-show="isUploading" class="space-y-2">
        <div class="flex justify-between text-sm text-gray-600">
            <span>Uploading...</span>
            <span x-text="uploadProgress + '%'"></span>
        </div>
        <div class="bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                 :style="'width: ' + uploadProgress + '%'"></div>
        </div>
    </div>

    <!-- Success Message -->
    <div x-show="showSuccess" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="p-3 bg-green-100 text-green-700 rounded-lg text-sm">
        ✅ Image uploaded successfully!
    </div>

    <!-- Error Message -->
    <div x-show="error" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="p-3 bg-red-100 text-red-700 rounded-lg text-sm"
         x-text="error"></div>

    <!-- Preview -->
    <div x-show="uploadedPath" class="space-y-3">
        <div class="relative inline-block">
            <img :src="previewUrl" 
                 alt="Uploaded image" 
                 class="w-full max-w-sm h-48 object-cover rounded-lg border shadow-sm">
            <button @click="removeImage()" 
                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                    title="Remove image">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="bg-gray-50 p-3 rounded-lg">
            <p class="text-sm text-gray-600">
                <strong>Path:</strong> <span x-text="uploadedPath"></span>
            </p>
            <p class="text-xs text-gray-500 mt-1">✅ Automatically saved to form</p>
        </div>
        
        <button @click="reset()" 
                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            Upload different image
        </button>
    </div>

    <!-- Hidden input for form -->
    <input type="hidden" 
           name="{{ $fieldName }}" 
           :value="uploadedPath" 
           x-ref="hiddenInput">
</div>

<script>
function imageUpload(config) {
    return {
        isDragging: false,
        isUploading: false,
        uploadProgress: 0,
        uploadedPath: '',
        previewUrl: '',
        error: '',
        showSuccess: false,
        
        init() {
            if (config.initialValue) {
                this.uploadedPath = config.initialValue;
                this.previewUrl = '/storage/' + config.initialValue;
            }
        },
        
        handleDrop(e) {
            this.isDragging = false;
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                this.handleFile(files[0]);
            }
        },
        
        handleFile(file) {
            if (!file || !file.type.startsWith('image/')) {
                this.showError('Please select a valid image file');
                return;
            }
            
            if (file.size > 2048 * 1024) {
                this.showError('File size must be less than 2MB');
                return;
            }
            
            this.uploadFile(file);
        },
        
        uploadFile(file) {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('type', config.type);
            formData.append('name', config.customName);
            
            this.isUploading = true;
            this.uploadProgress = 0;
            this.error = '';
            
            // Simulate progress
            const progressInterval = setInterval(() => {
                if (this.uploadProgress < 90) {
                    this.uploadProgress += Math.random() * 30;
                }
            }, 200);
            
            fetch('/admin/upload-image', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                this.uploadProgress = 100;
                
                if (data.success) {
                    this.uploadedPath = data.path;
                    this.previewUrl = data.url;
                    this.isUploading = false;
                    this.showSuccess = true;
                    
                    // Auto-hide success message
                    setTimeout(() => {
                        this.showSuccess = false;
                    }, 3000);
                    
                    // Update form field
                    this.$refs.hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                } else {
                    this.showError(data.error || 'Upload failed');
                }
            })
            .catch(error => {
                clearInterval(progressInterval);
                this.showError('Network error: ' + error.message);
            });
        },
        
        removeImage() {
            // Could implement server-side deletion here
            this.reset();
        },
        
        reset() {
            this.uploadedPath = '';
            this.previewUrl = '';
            this.isUploading = false;
            this.uploadProgress = 0;
            this.error = '';
            this.showSuccess = false;
        },
        
        showError(message) {
            this.error = message;
            this.isUploading = false;
            this.uploadProgress = 0;
            setTimeout(() => {
                this.error = '';
            }, 5000);
        }
    }
}
</script>
