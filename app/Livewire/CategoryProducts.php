<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Product;

class CategoryProducts extends Component
{
    use WithPagination;

    public $categorySlug;
    public $sortBy = 'latest';

    public function mount($categorySlug)
    {
        $this->categorySlug = $categorySlug;
    }

    public function render()
    {
        $category = Category::where('slug', $this->categorySlug)->firstOrFail();

        $query = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->with(['category', 'images']);

        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        return view('livewire.category-products', [
            'products' => $products,
            'category' => $category
        ]);
    }
}
