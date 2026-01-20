<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class CartIcon extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    public function updateCartCount()
    {
        if (auth()->check()) {
            $this->cartCount = (int) CartItem::where('user_id', auth()->id())
                ->whereHas('product')
                ->sum('quantity');
        } else {
            $this->cartCount = (int) CartItem::where('session_id', session()->getId())
                ->whereHas('product')
                ->sum('quantity');
        }
    }

    public function render()
    {
        return view('livewire.cart-icon');
    }
}
