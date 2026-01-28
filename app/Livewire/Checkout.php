<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Address;
use App\Models\DeliveryZone;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\RazorpayService;
use App\Models\Order;
use App\Models\OrderItem;


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
        $this->isServiceable = true; // Forced to true for testing
        $this->deliveryFee = 0;

        /*
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
        */
    }

    public function proceedToReview()
    {
        if (!$this->selectedAddressId) {
            $this->addError('address', 'Please select a delivery address');
            return;
        }

        // if (!$this->isServiceable) {
        //     $this->addError('address', $this->deliveryError ?: 'Address not serviceable');
        //     return;
        // }
        
        $this->dispatch('initiate-razorpay', [
            'amount' => $this->subtotal + $this->deliveryFee,
            'email' => Auth::user()->email ?? 'guest@example.com',
            'contact' => $this->addresses->find($this->selectedAddressId)->phone ?? '',
            'name' => Auth::user()->name ?? 'Guest User'
        ]);

        $this->currentStep = 2;
    }

    public function initiatePayment()
    {
        \Illuminate\Support\Facades\Log::info('Initiating payment...');
        
        $this->validate([
            'selectedAddressId' => 'required'
        ]);

        $amount = $this->subtotal + $this->deliveryFee;
        $receiptId = 'rcpt_' . time();
        
        \Illuminate\Support\Facades\Log::info('Creating Razorpay order for amount: ' . $amount);

        try {
            $razorpayService = new RazorpayService();
            $razorpayOrder = $razorpayService->createOrder($amount, $receiptId);
            
            \Illuminate\Support\Facades\Log::info('Razorpay Order Created: ' . $razorpayOrder->id);
            
            // Ensure Customer record exists
            $user = Auth::user();
            $customer = $user->customer;
            
            if (!$customer) {
                \Illuminate\Support\Facades\Log::info('Creating missing customer record for user: ' . $user->id);
                // Create customer from user details
                $nameParts = explode(' ', $user->name, 2);
                $customer = \App\Models\Customer::create([
                    'user_id' => $user->id,
                    'first_name' => $nameParts[0],
                    'last_name' => $nameParts[1] ?? '',
                    'email' => $user->email,
                    'phone' => $this->addresses->find($this->selectedAddressId)->phone ?? null,
                ]);
            }

            // Create pending order in DB
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending',
                'subtotal' => $this->subtotal,
                'shipping' => $this->deliveryFee, 
                'total' => $amount,
                'payment_method' => 'razorpay',
                'payment_status' => 'pending',
                'shipping_address' => json_encode($this->addresses->find($this->selectedAddressId)),
                'billing_address' => json_encode($this->addresses->find($this->selectedAddressId)),
                'razorpay_order_id' => $razorpayOrder->id,
            ]);
            
            \Illuminate\Support\Facades\Log::info('Local Order Created: ' . $order->id);

            // Add Items
            foreach($this->cartItems as $item) {
                // Determine name and SKU
                $productName = $item->product->name;
                $sku = $item->product->sku;

                if ($item->productVariant) {
                     $productName .= ' (' . $item->productVariant->name . ')';
                     $sku = $item->productVariant->sku ?? $sku; // Use variant SKU if available
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $productName, // Added
                    'product_sku' => $sku,         // Added
                    'quantity' => $item->quantity,
                    'price' => $item->productVariant ? $item->productVariant->price : $item->product->effective_price,
                    'total' => ($item->productVariant ? $item->productVariant->price : $item->product->effective_price) * $item->quantity,
                ]);
            }

            // Dispatch event to open Razorpay modal
            $this->dispatch('open-razorpay-modal', [
                'razorpay_order_id' => $razorpayOrder->id,
                'amount' => $amount * 100,
                'key' => config('services.razorpay.key'),
                'name' => config('app.name'),
                'description' => 'Order Payment',
                'prefill' => [
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'contact' => $this->addresses->find($this->selectedAddressId)->phone,
                ]
            ]);
            
            \Illuminate\Support\Facades\Log::info('Dispatching modal event');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment initiation failed: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            $this->addError('payment', 'Payment initiation failed: ' . $e->getMessage());
        }
    }

    public function handlePaymentSuccess($response)
    {
        $signatureStatus = (new RazorpayService())->verifySignature($response);

        if ($signatureStatus) {
            $order = Order::where('razorpay_order_id', $response['razorpay_order_id'])->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'razorpay_payment_id' => $response['razorpay_payment_id'],
                    'razorpay_signature' => $response['razorpay_signature'],
                ]);

                // Clear Cart
                CartItem::where('user_id', Auth::id())->delete();
                
                return redirect()->route('orders.success', $order->id); // Define this route
            }
        } else {
             $this->addError('payment', 'Payment verification failed.');
        }
    }


    public function render()
    {
        return view('livewire.checkout', [
            'total' => $this->subtotal + $this->deliveryFee
        ]);
    }
}
