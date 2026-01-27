<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\CartItem;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class ProductDetail extends Component
{
    use WithPagination;
    public $productSlug;
    public $product;
    public $quantity = 1;
    public $selectedImage = null;
    public $selectedVariant = null;

    public function mount($productSlug)
    {
        $this->productSlug = $productSlug;
        $this->product = Product::where('slug', $productSlug)
            ->where('is_active', true)
            ->with(['category', 'images.media', 'variants', 'seo'])
            ->firstOrFail();
        
        if ($this->product->primary_image) {
            $this->selectedImage = $this->product->primary_image->large_url;
        } elseif ($this->product->images->isNotEmpty()) {
             $this->selectedImage = $this->product->images->first()->large_url;
        }
    }



    public function incrementQuantity()
    {
        if ($this->quantity < $this->product->stock_quantity) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart()
    {
        // Check if requested quantity is valid locally
        if ($this->product->stock_quantity < $this->quantity) {
            session()->flash('error', 'Not enough stock available.');
            return;
        }

        // Check for existing cart item to validate total quantity
        $existingItem = CartItem::where('product_id', $this->product->id)
            ->where(auth()->check() ? 'user_id' : 'session_id', auth()->check() ? auth()->id() : session()->getId())
            ->first();

        $currentInCart = $existingItem ? $existingItem->quantity : 0;

        if (($currentInCart + $this->quantity) > $this->product->stock_quantity) {
            session()->flash('error', 'You cannot add that amount. You already have ' . $currentInCart . ' in your cart and we only have ' . $this->product->stock_quantity . ' in stock.');
            return;
        }

        $cartData = [
            'product_id' => $this->product->id,
            'quantity' => $this->quantity, // Initial quantity for new item
            'product_variant_id' => $this->selectedVariant,
        ];

        if (auth()->check()) {
            $cartData['user_id'] = auth()->id();
            $cartData['session_id'] = null;
        } else {
            $cartData['session_id'] = session()->getId();
            $cartData['user_id'] = null;
        }

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $this->quantity
            ]);
        } else {
            CartItem::create($cartData);
        }

        $this->dispatch('cart-updated');
        session()->flash('success', 'Product added to cart!');
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.product-detail', [
            'reviews' => $this->product->reviews()->with('customer')->latest()->paginate(5),
        ]);
    }
}
