<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

use Livewire\Attributes\Lazy;

#[Lazy]
class RelatedProducts extends Component
{
    public $productId;
    public $categoryId;

    public function mount($productId, $categoryId)
    {
        $this->productId = $productId;
        $this->categoryId = $categoryId;
    }

    public function render()
    {
        $relatedProducts = Product::where('category_id', $this->categoryId)
            ->where('id', '!=', $this->productId)
            ->where('is_active', true)
            ->with(['category', 'images'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('livewire.related-products', [
            'relatedProducts' => $relatedProducts
        ]);
    }
}
