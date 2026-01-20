<?php

namespace App\Livewire;

use Livewire\Component;

class ProductGridBlock extends Component
{
    public $type = 'featured';
    public $count = 4;

    public function render()
    {
        $query = \App\Models\Product::query()
            ->where('is_active', true)
            ->with('images');

        if ($this->type === 'featured') {
            $query->where('is_featured', true);
        } elseif ($this->type === 'sale') {
            $query->whereNotNull('discount_price');
        } else {
            // Latest
            $query->latest();
        }

        $products = $query->take($this->count)->get();

        return view('livewire.product-grid-block', [
            'products' => $products
        ]);
    }
}
