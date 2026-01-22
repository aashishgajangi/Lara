<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Livewire\CartIcon;

class ShoppingCart extends Component
{
    public $couponCode = '';
    public $appliedCoupon = null;
    public $couponError = '';
    public $shippingMethod = 'standard';
    
    public $shippingRates = [
        'standard' => ['name' => 'Standard Shipping (5-7 days)', 'cost' => 0],
        'express' => ['name' => 'Express Shipping (2-3 days)', 'cost' => 15],
        'overnight' => ['name' => 'Overnight Shipping (1 day)', 'cost' => 30],
    ];

    public function mount()
    {
        $this->appliedCoupon = session('applied_coupon');
    }

    public function applyCoupon()
    {
        $this->couponError = '';
        
        if (empty($this->couponCode)) {
            $this->couponError = 'Please enter a coupon code.';
            return;
        }

        $coupon = Coupon::where('code', strtoupper($this->couponCode))->first();

        if (!$coupon) {
            $this->couponError = 'Invalid coupon code.';
            return;
        }

        $subtotal = $this->calculateSubtotal();

        if (!$coupon->isValid($subtotal)) {
            if ($coupon->min_purchase_amount && $subtotal < $coupon->min_purchase_amount) {
                $this->couponError = 'Minimum purchase amount of $' . number_format($coupon->min_purchase_amount, 2) . ' required.';
            } elseif ($coupon->usage_limit && $coupon->usage_count >= $coupon->usage_limit) {
                $this->couponError = 'This coupon has reached its usage limit.';
            } elseif ($coupon->valid_until && now()->gt($coupon->valid_until)) {
                $this->couponError = 'This coupon has expired.';
            } else {
                $this->couponError = 'This coupon is not valid.';
            }
            return;
        }

        $this->appliedCoupon = $coupon;
        session(['applied_coupon' => $coupon]);
        session()->flash('success', 'Coupon applied successfully!');
    }

    public function removeCoupon()
    {
        $this->appliedCoupon = null;
        $this->couponCode = '';
        session()->forget('applied_coupon');
        session()->flash('success', 'Coupon removed.');
    }

    private function calculateSubtotal()
    {
        $cartItems = $this->getCartItems();
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->product->getEffectivePrice() * $item->quantity;
        }

        return $subtotal;
    }

    private function getCartItems()
    {
        if (auth()->check()) {
            return CartItem::where('user_id', auth()->id())
            ->with('product.images.media', 'product.category')
                ->get();
        }
        
        return CartItem::where('session_id', session()->getId())
            ->with('product.images.media', 'product.category')
            ->get();
    }

    public function render()
    {
        $cartItems = $this->getCartItems();
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->product->getEffectivePrice() * $item->quantity;
        }
        
        $discount = 0;
        if ($this->appliedCoupon) {
            $discount = $this->appliedCoupon->calculateDiscount($subtotal);
        }

        $shipping = $this->shippingRates[$this->shippingMethod]['cost'];
        $tax = ($subtotal - $discount) * 0.1; // 10% tax
        $total = $subtotal - $discount + $shipping + $tax;

        return view('livewire.shopping-cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'tax' => $tax,
            'total' => $total
        ]);
    }

    public function removeItem($itemId)
    {
        CartItem::find($itemId)->delete();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Item removed from cart.');
    }

    public function updateQuantity($itemId, $quantity)
    {
        $quantity = (int) $quantity;
        
        if ($quantity > 0) {
            $item = CartItem::find($itemId);
            if ($item && $item->product->stock_quantity >= $quantity) {
                $item->update(['quantity' => $quantity]);
                $this->dispatch('cart-updated');
                $this->dispatch('$refresh');
            } else {
                session()->flash('error', 'Not enough stock available.');
            }
        } elseif ($quantity === 0) {
            $this->removeItem($itemId);
        }
    }
    
    public function incrementQuantity($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item && $item->product->stock_quantity > $item->quantity) {
            $item->increment('quantity');
            $this->dispatch('cart-updated');
        }
    }
    
    public function decrementQuantity($itemId)
    {
        $item = CartItem::find($itemId);
        if ($item) {
            if ($item->quantity > 1) {
                $item->decrement('quantity');
                $this->dispatch('cart-updated');
            } else {
                $this->removeItem($itemId);
            }
        }
    }

    public function clearCart()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            CartItem::where('session_id', session()->getId())->delete();
        }
        
        $this->removeCoupon();
        $this->dispatch('cart-updated');
        session()->flash('success', 'Cart cleared.');
    }
}
