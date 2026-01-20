<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploader extends Component
{
    use WithFileUploads;

    public $image;
    public $uploadedImagePath;
    public $imageType = 'general'; // categories, products, general
    public $customName = '';
    public $isUploading = false;
    public $uploadProgress = 0;

    protected $rules = [
        'image' => 'required|image|max:2048', // 2MB max
    ];

    public function mount($type = 'general', $name = '')
    {
        $this->imageType = $type;
        $this->customName = $name;
    }

    public function updatedImage()
    {
        $this->validate();
        $this->uploadImage();
    }

    public function uploadImage()
    {
        if (!$this->image) {
            return;
        }

        $this->isUploading = true;
        $this->uploadProgress = 50;

        try {
            // Create organized folder structure: type/YYYY/MM/DD
            $folderPath = sprintf(
                '%s/%s/%s/%s',
                $this->imageType,
                now()->format('Y'),
                now()->format('m'),
                now()->format('d')
            );

            // Generate SEO-friendly filename
            $originalName = pathinfo($this->image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $this->image->getClientOriginalExtension();
            $seoName = $this->customName ?: Str::slug($originalName);
            $filename = sprintf('%s-%s.%s', $seoName, now()->timestamp, $extension);

            // Store the image
            $path = $this->image->storeAs($folderPath, $filename, 'public');
            
            $this->uploadedImagePath = $path;
            $this->uploadProgress = 100;
            
            // Emit event with the uploaded image path
            $this->dispatch('imageUploaded', path: $path);
            
            session()->flash('image-success', 'Image uploaded successfully!');
            
        } catch (\Exception $e) {
            session()->flash('image-error', 'Upload failed: ' . $e->getMessage());
        }

        $this->isUploading = false;
        $this->reset('image');
    }

    public function removeImage()
    {
        if ($this->uploadedImagePath && Storage::disk('public')->exists($this->uploadedImagePath)) {
            Storage::disk('public')->delete($this->uploadedImagePath);
        }
        
        $this->reset(['uploadedImagePath', 'uploadProgress']);
        $this->dispatch('imageRemoved');
    }

    public function render()
    {
        return view('livewire.image-uploader');
    }
}
