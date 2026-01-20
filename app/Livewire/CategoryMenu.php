<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

class CategoryMenu extends Component
{
    public function render()
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        return view('livewire.category-menu', [
            'categories' => $categories
        ]);
    }
}
