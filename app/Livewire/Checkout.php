<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Address;
use App\Models\DeliveryZone;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Checkout extends Component
{
    // Steps: 1 = Address, 2 = Review/Payment
    public $currentStep = 1;

    // Address Management
    public $addresses;
    public $selectedAddressId;
    public $isAddingNewAddress = false;
    
    // New Address Form
    public $newAddress = [
        'type' => 'home',
        'name' => '',
        'phone' => '',
        'address_line1' => '',
        'address_line2' => '',
        'city' => '',
        'state' => '',
        'pincode' => '',
        'country' => 'India',
        'lat' => null,
        'lng' => null,
        'is_default' => false,
    ];

    // Order/Delivery Data
    public $cartItems;
    public $subtotal = 0;
    public $deliveryFee = 0;
    public $isServiceable = true;
    public $deliveryError = '';

    public function mount()
    {
        $this->loadCart();
        $this->loadAddresses();
        
        // Pre-select default address if available
        $default = $this->addresses->where('is_default', true)->first();
        if ($default) {
            $this->selectedAddressId = $default->id;
            $this->validateDelivery($default->pincode);
        }
    }

    public function loadCart()
    {
        // Logic to retrieve cart items (copied/adapted from previous Cart implementations)
        if (Auth::check()) {
            $this->cartItems = CartItem::where('user_id', Auth::id())->with(['product', 'productVariant'])->get();
        } else {
            $this->cartItems = CartItem::where('session_id', session()->getId())->with(['product', 'productVariant'])->get();
        }

        $this->subtotal = $this->cartItems->sum(function($item) {
            $price = $item->productVariant ? $item->productVariant->price : $item->product->effective_price;
            return $item->quantity * $price;
        });

        if ($this->cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }
    }

    public function loadAddresses()
    {
        if (Auth::check()) {
            $this->addresses = Address::where('user_id', Auth::id())->get();
        } else {
            $this->addresses = collect([]); // Guest checkout address handling could be added here
        }
    }

    public function toggleNewAddressForm()
    {
        $this->isAddingNewAddress = !$this->isAddingNewAddress;
        // Reset form if opening
        if ($this->isAddingNewAddress) {
            $this->resetNewAddress();
        }
    }

    public function resetNewAddress()
    {
        $this->newAddress = [
            'type' => 'home',
            'name' => Auth::user()->name ?? '',
            'phone' => '', // Could pull from user profile if available
            'address_line1' => '',
            'address_line2' => '',
            'city' => '',
            'state' => '',
            'pincode' => '',
            'country' => 'India',
            'lat' => null,
            'lng' => null,
            'is_default' => false,
        ];
    }

    public function saveNewAddress()
    {
        // Check authentication
        if (!Auth::check()) {
            $this->addError('general', 'You must be logged in to save an address. Please log in and try again.');
            return;
        }

        $this->validate([
            'newAddress.name' => 'required',
            'newAddress.phone' => 'required',
            'newAddress.address_line1' => 'required',
            'newAddress.city' => 'required',
            'newAddress.state' => 'required',
            'newAddress.pincode' => 'required', // Removed strict digits:6 to allow cleaning first
            'newAddress.lat' => 'nullable|numeric',
            'newAddress.lng' => 'nullable|numeric',
        ]);

        // Clean pincode
        $pincode = preg_replace('/\s+/', '', $this->newAddress['pincode']);

        // Validate pincode format after cleaning
        if (!preg_match('/^\d{6}$/', $pincode)) {
             $this->addError('newAddress.pincode', 'The pincode must be 6 digits.');
             return;
        }

        try {
            $address = Address::create([
                'user_id' => Auth::id(),
                'type' => $this->newAddress['type'],
                'name' => $this->newAddress['name'],
                'phone' => $this->newAddress['phone'],
                'address_line1' => $this->newAddress['address_line1'],
                'address_line2' => $this->newAddress['address_line2'],
                'city' => $this->newAddress['city'],
                'state' => $this->newAddress['state'],
                'pincode' => $pincode,
                'country' => $this->newAddress['country'],
                'lat' => $this->newAddress['lat'] ?? null,
                'lng' => $this->newAddress['lng'] ?? null,
                'is_default' => $this->addresses->isEmpty() ? true : $this->newAddress['is_default'],
            ]);

            $this->loadAddresses();
            $this->selectedAddressId = $address->id;
            $this->isAddingNewAddress = false;
            $this->validateDelivery($address->pincode);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Address Save Error: ' . $e->getMessage());
            $this->addError('general', 'Failed to save address: ' . $e->getMessage());
        }
    }

    public function selectAddress($addressId)
    {
        $this->selectedAddressId = $addressId;
        $address = $this->addresses->find($addressId);
        if ($address) {
            $this->validateDelivery($address->pincode);
        }
    }

    public function validateDelivery($pincode)
    {
        $this->deliveryError = '';
        $this->isServiceable = false;
        $this->deliveryFee = 0;

        $zone = DeliveryZone::where('pincode', $pincode)->where('is_active', true)->first();

        if (!$zone) {
            $this->deliveryError = 'Sorry, we do not deliver to this location yet.';
            return;
        }

        if ($this->subtotal < $zone->min_order_amount) {
            $this->deliveryError = 'Minimum order amount for this location is ₹' . $zone->min_order_amount;
            return;
        }

        $this->isServiceable = true;
        $this->deliveryFee = $zone->delivery_fee;
    }

    public function proceedToReview()
    {
        if (!$this->selectedAddressId) {
            $this->addError('address', 'Please select a delivery address');
            return;
        }

        if (!$this->isServiceable) {
            $this->addError('address', $this->deliveryError ?: 'Address not serviceable');
            return;
        }

        $this->currentStep = 2;
    }

    public function render()
    {
        return view('livewire.checkout', [
            'total' => $this->subtotal + $this->deliveryFee
        ]);
    }
}
