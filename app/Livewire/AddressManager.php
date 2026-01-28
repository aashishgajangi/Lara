<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressManager extends Component
{
    public $addresses;
    public $isEditing = false;
    public $editingAddressId = null;
    
    public $editAddress = [
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

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = Address::where('user_id', Auth::id())->get();
    }

    public function startEdit($addressId)
    {
        $address = Address::where('id', $addressId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $this->editingAddressId = $addressId;
        $this->isEditing = true;
        
        $this->editAddress = [
            'type' => $address->type,
            'name' => $address->name,
            'phone' => $address->phone,
            'address_line1' => $address->address_line1,
            'address_line2' => $address->address_line2,
            'city' => $address->city,
            'state' => $address->state,
            'pincode' => $address->pincode,
            'country' => $address->country,
            'lat' => $address->lat,
            'lng' => $address->lng,
            'is_default' => $address->is_default,
        ];
    }

    public function updateAddress()
    {
        $this->validate([
            'editAddress.name' => 'required',
            'editAddress.phone' => 'required',
            'editAddress.address_line1' => 'required',
            'editAddress.city' => 'required',
            'editAddress.state' => 'required',
            'editAddress.pincode' => 'required',
            'editAddress.lat' => 'nullable|numeric',
            'editAddress.lng' => 'nullable|numeric',
        ]);

        // Clean pincode
        $pincode = preg_replace('/\s+/', '', $this->editAddress['pincode']);

        // Validate pincode format
        if (!preg_match('/^\d{6}$/', $pincode)) {
            $this->addError('editAddress.pincode', 'The pincode must be 6 digits.');
            return;
        }

        try {
            $address = Address::where('id', $this->editingAddressId)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $address->update([
                'type' => $this->editAddress['type'],
                'name' => $this->editAddress['name'],
                'phone' => $this->editAddress['phone'],
                'address_line1' => $this->editAddress['address_line1'],
                'address_line2' => $this->editAddress['address_line2'],
                'city' => $this->editAddress['city'],
                'state' => $this->editAddress['state'],
                'pincode' => $pincode,
                'country' => $this->editAddress['country'],
                'lat' => $this->editAddress['lat'] ?? null,
                'lng' => $this->editAddress['lng'] ?? null,
                'is_default' => $this->editAddress['is_default'],
            ]);

            $this->loadAddresses();
            $this->cancelEdit();
            session()->flash('message', 'Address updated successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Address Update Error: ' . $e->getMessage());
            $this->addError('general', 'Failed to update address: ' . $e->getMessage());
        }
    }

    public function deleteAddress($addressId)
    {
        try {
            Address::where('id', $addressId)
                ->where('user_id', Auth::id())
                ->delete();
            
            $this->loadAddresses();
            session()->flash('message', 'Address deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete address.');
        }
    }

    public function setAsDefault($addressId)
    {
        // Remove default from all addresses
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        
        // Set selected as default
        Address::where('id', $addressId)
            ->where('user_id', Auth::id())
            ->update(['is_default' => true]);
        
        $this->loadAddresses();
        session()->flash('message', 'Default address updated!');
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingAddressId = null;
        $this->reset('editAddress');
    }

    public function render()
    {
        return view('livewire.address-manager');
    }
}
