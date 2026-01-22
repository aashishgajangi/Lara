<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class FeaturedProducts extends Component
{
    public function render()
    {
        $products = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'images.media'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('livewire.featured-products', [
            'products' => $products
        ]);
    }
}
