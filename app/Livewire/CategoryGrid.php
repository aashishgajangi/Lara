<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryGrid extends Component
{
    public function render()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('livewire.category-grid', [
            'categories' => $categories
        ]);
    }
}
