<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center relative">
                        <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 {{ $currentStep >= 1 ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300' }} flex items-center justify-center">
                            1
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase {{ $currentStep >= 1 ? 'text-primary-600' : 'text-gray-500' }}">Address</div>
                    </div>
                    <div class="flex-auto border-t-2 transition duration-500 ease-in-out {{ $currentStep >= 2 ? 'border-primary-600' : 'border-gray-300' }} w-32"></div>
                    <div class="flex items-center relative">
                        <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 {{ $currentStep >= 2 ? 'border-primary-600 bg-primary-600 text-white' : 'border-gray-300' }} flex items-center justify-center">
                            2
                        </div>
                        <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase {{ $currentStep >= 2 ? 'text-primary-600' : 'text-gray-500' }}">Review & Pay</div>
                    </div>
                </div>
            </div>
        </div>

        @if($currentStep == 1)
            <!-- Address Selection Step -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Saved Addresses -->
                <div>
                    <h2 class="text-xl font-bold mb-4">Select Delivery Address</h2>
                    
                    @if($addresses->isEmpty())
                        <div class="text-gray-500 mb-4">No saved addresses found. Please add one.</div>
                    @else
                        <div class="space-y-4 mb-6">
                            @foreach($addresses as $address)
                                <div class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition relative {{ $selectedAddressId == $address->id ? 'border-primary-600 bg-blue-50' : 'border-gray-200' }}"
                                     wire:click="selectAddress({{ $address->id }})">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $address->name }} <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded ml-2 uppercase font-medium">{{ $address->type }}</span></div>
                                            <div class="text-sm text-gray-600 mt-1">
                                                {{ $address->address_line1 }}<br>
                                                {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                                            </div>
                                            <div class="text-sm text-gray-600 mt-1">Ph: {{ $address->phone }}</div>
                                        </div>
                                        @if($selectedAddressId == $address->id)
                                            <div class="text-primary-600">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <button wire:click="toggleNewAddressForm" class="w-full py-3 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-primary-500 hover:text-primary-600 transition flex items-center justify-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add New Address
                    </button>

                    <!-- New Address Form -->
                    @if($isAddingNewAddress)
                            <div class="mt-6 border-2 border-gray-200 rounded-xl bg-white shadow-sm overflow-hidden" 
                                 x-data="{
                                    map: null,
                                    geocoder: null,
                                    isDragging: false,
                                    initMap() {
                                        this.waitForGoogleMaps();
                                    },
                                    waitForGoogleMaps() {
                                        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                                            this.loadMap();
                                        } else {
                                            setTimeout(() => this.waitForGoogleMaps(), 100);
                                        }
                                    },
                                    loadMap() {
                                        // Default: India Center
                                        const defaultLocation = { lat: 20.5937, lng: 78.9629 };
                                        
                                        this.map = new google.maps.Map(document.getElementById('map'), {
                                            center: defaultLocation,
                                            zoom: 5,
                                            disableDefaultUI: true,
                                            zoomControl: false,
                                            streetViewControl: false,
                                            gestureHandling: 'greedy'
                                        });

                                        this.geocoder = new google.maps.Geocoder();

                                        // Listen for drag/idle to geocode center
                                        this.map.addListener('dragstart', () => {
                                            this.isDragging = true;
                                        });

                                        this.map.addListener('idle', () => {
                                            this.isDragging = false;
                                            const center = this.map.getCenter();
                                            this.geocodePosition(center);
                                        });

                                        this.initAutocomplete();
                                        
                                        // Auto-detect location on load
                                        this.useCurrentLocation();
                                    },

                                    initAutocomplete() {
                                        const input = document.getElementById('address-input');
                                        if(!input) return;
                                        
                                        const autocomplete = new google.maps.places.Autocomplete(input, {
                                            componentRestrictions: { country: 'in' },
                                            fields: ['formatted_address', 'geometry', 'address_components'],
                                        });

                                        autocomplete.addListener('place_changed', () => {
                                            const place = autocomplete.getPlace();
                                            if (!place.geometry) {
                                                // window.alert('No details available for input: ' + place.name);
                                                return;
                                            }

                                            // Center map (triggers idle -> geocode)
                                            this.map.setCenter(place.geometry.location);
                                            this.map.setZoom(17);
                                        });
                                    },

                                    geocodePosition(pos) {
                                        this.geocoder.geocode({ location: pos }, (results, status) => {
                                            if (status === 'OK') {
                                                if (results[0]) {
                                                    this.fillInAddress(results[0]);
                                                }
                                            }
                                        });
                                    },

                                    useCurrentLocation() {
                                        if (!navigator.geolocation) {
                                            // alert('Geolocation is not supported by this browser.');
                                            return;
                                        }

                                        const inputBtn = this.$el; // This might be undefined if called from init
                                        let originalContent = '';
                                        if(inputBtn && inputBtn.tagName === 'BUTTON') {
                                             originalContent = inputBtn.innerHTML;
                                             inputBtn.innerHTML = '<span class=\'animate-pulse\'>Locating...</span>';
                                             inputBtn.disabled = true;
                                        }

                                        navigator.geolocation.getCurrentPosition((position) => {
                                            const pos = {
                                                lat: position.coords.latitude,
                                                lng: position.coords.longitude
                                            };

                                            if(this.map) {
                                                this.map.setCenter(pos);
                                                this.map.setZoom(17);
                                            }
                                            
                                            // Trigger geocode explicitly in case idle doesn't fire immediately or map didn't move far
                                            this.geocodePosition(pos);
                                            
                                            if(inputBtn && inputBtn.tagName === 'BUTTON') {
                                                inputBtn.innerHTML = originalContent;
                                                inputBtn.disabled = false;
                                            }
                                        }, (error) => {
                                            if(inputBtn && inputBtn.tagName === 'BUTTON') {
                                                inputBtn.innerHTML = originalContent;
                                                inputBtn.disabled = false;
                                            }
                                            console.warn('Geolocation failed: ' + error.message);
                                            // alert('Unable to retrieve your location.'); 
                                            // Don't alert on auto-load failure, it's annoying
                                        });
                                    },

                                    fillInAddress(place) {
                                        let address1 = '';
                                        let pincode = '';
                                        let city = '';
                                        let state = '';
                                        
                                        // Update Lat/Lng
                                        const lat = typeof place.geometry.location.lat === 'function' ? place.geometry.location.lat() : place.geometry.location.lat;
                                        const lng = typeof place.geometry.location.lng === 'function' ? place.geometry.location.lng() : place.geometry.location.lng;
                                        
                                        @this.set('newAddress.lat', lat);
                                        @this.set('newAddress.lng', lng);

                                        for (const component of place.address_components) {
                                            const componentType = component.types[0];
                                            switch (componentType) {
                                                case 'street_number': {
                                                    address1 = `${component.long_name} ${address1}`;
                                                    break;
                                                }
                                                case 'route': {
                                                    address1 += component.short_name;
                                                    break;
                                                }
                                                case 'postal_code': {
                                                    pincode = component.long_name;
                                                    break;
                                                }
                                                case 'locality': {
                                                    city = component.long_name;
                                                    break;
                                                }
                                                case 'administrative_area_level_1': {
                                                    state = component.short_name;
                                                    break;
                                                }
                                            }
                                        }

                                        @this.set('newAddress.address_line1', address1);
                                        @this.set('newAddress.city', city);
                                        @this.set('newAddress.state', state);
                                        @this.set('newAddress.pincode', pincode);
                                    }
                                }"
                                x-init="initMap()">
                                
                            <div class="p-6">
                                <h3 class="text-lg font-bold mb-4">Add New Address</h3>

                                <!-- Search & Locate -->
                                    <div wire:ignore class="mb-4 relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </div>
                                        <input type="text" id="address-input" placeholder="Search for area, street name..." class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 py-3">
                                    </div>

                                    @error('general')
                                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                            <strong class="font-bold">Error!</strong>
                                            <span class="block sm:inline">{{ $message }}</span>
                                        </div>
                                    @enderror
                                
                                <!-- Map Container -->
                                <div wire:ignore class="relative mb-8 rounded-xl overflow-hidden shadow-lg border border-gray-200 group">
                                    <div id="map" class="w-full bg-gray-100" style="height: 320px;"></div>
                                    
                                    <!-- Center Pin (Fixed) -->
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; z-index: 20; padding-bottom: 38px;">
                                        <!-- Pin SVG -->
                                        <svg height="40px" width="40px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" fill="#ef4444">
                                            <path style="fill:#EF4444;" d="M256,0C153.755,0,70.573,83.182,70.573,185.426c0,126.888,165.939,313.167,173.004,321.035
                                                c6.636,7.391,18.222,7.378,24.846,0c7.065-7.868,173.004-194.147,173.004-321.035C441.425,83.182,358.244,0,256,0z M256,278.719
                                                c-51.442,0-93.292-41.851-93.292-93.293S204.559,92.134,256,92.134s93.291,41.851,93.291,93.293S307.441,278.719,256,278.719z"/>
                                        </svg>
                                        <!-- Pulse Effect -->
                                        <div style="position: absolute; bottom: 38px; left: 50%; transform: translateX(-50%); width: 8px; height: 8px; background: #ef4444; border-radius: 9999px; animation: pulse 2s infinite; opacity: 0.75;"></div>
                                    </div>

                                    <!-- Move Pin Tooltip -->
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -80px); z-index: 10; pointer-events: none; transition: opacity 0.3s;"
                                         :style="{ opacity: isDragging ? 0 : 1 }">
                                        <div style="background: #1f2937; color: white; font-size: 0.875rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); white-space: nowrap; position: relative;">
                                            Move pin to your exact delivery location
                                            <div style="position: absolute; bottom: -4px; left: 50%; transform: translateX(-50%); width: 12px; height: 12px; background: #1f2937; transform: translateX(-50%) rotate(45deg);"></div>
                                        </div>
                                    </div>

                                    <!-- Use Current Location Floating Button -->
                                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-10 w-full max-w-xs px-4">
                                        <button type="button" @click="useCurrentLocation()" class="w-full bg-white text-red-500 font-semibold py-2.5 px-4 rounded-full shadow-lg border border-gray-100 flex items-center justify-center gap-2 hover:bg-gray-50 transition">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            Use current location
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                        <input type="text" wire:model="newAddress.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @error('newAddress.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                                        <input type="text" wire:model="newAddress.phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @error('newAddress.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Flat, House no., Building, Company, Apartment</label>
                                        <input type="text" wire:model="newAddress.address_line1" id="address_line1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        @error('newAddress.address_line1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Area, Street, Sector, Village (Optional)</label>
                                        <input type="text" wire:model="newAddress.address_line2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">City</label>
                                            <input type="text" wire:model="newAddress.city" id="city" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                            @error('newAddress.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">State</label>
                                            <input type="text" wire:model="newAddress.state" id="state" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                            @error('newAddress.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Pincode</label>
                                            <input type="text" wire:model="newAddress.pincode" id="postal_code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                            @error('newAddress.pincode') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Type</label>
                                            <div class="mt-1 flex gap-3">
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="newAddress.type" value="home" class="text-primary-600">
                                                    <span class="text-sm">Home</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="newAddress.type" value="work" class="text-primary-600">
                                                    <span class="text-sm">Work</span>
                                                </label>
                                                <label class="flex items-center gap-1 cursor-pointer">
                                                    <input type="radio" wire:model="newAddress.type" value="other" class="text-primary-600">
                                                    <span class="text-sm">Other</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="newAddress.is_default" class="h-4 w-4 text-primary-600 border-gray-300 rounded">
                                        <label class="ml-2 block text-sm text-gray-900">Make this my default address</label>
                                    </div>

                                    <div class="flex gap-4 pt-4">
                                        <button wire:click="saveNewAddress" class="flex-1 bg-primary-600 text-white px-4 py-3 rounded-lg font-bold hover:bg-primary-700 shadow-lg">Save Address</button>
                                        <button wire:click="toggleNewAddressForm" class="px-4 py-3 text-gray-600 font-medium hover:bg-gray-100 rounded-lg">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 p-6 rounded-lg h-fit">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                    <div class="space-y-2 mb-4">
                        @foreach($cartItems as $item)
                        <div class="flex justify-between text-sm">
                            <span>
                                {{ $item->product->name }} 
                                @if($item->productVariant)
                                    ({{ $item->productVariant->name }})
                                @endif
                                x {{ $item->quantity }}
                            </span>
                            <span>₹{{ ($item->productVariant ? $item->productVariant->price : $item->product->effective_price) * $item->quantity }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="border-t pt-2 space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between font-medium {{ $isServiceable ? 'text-green-600' : 'text-gray-500' }}">
                            <span>Delivery Fee</span>
                            @if($selectedAddressId)
                                @if($isServiceable)
                                    <span>₹{{ number_format($deliveryFee, 2) }}</span>
                                @else
                                    <span class="text-red-500">Not Serviceable</span>
                                @endif
                            @else
                                <span class="text-gray-400">Select address</span>
                            @endif
                        </div>
                        @if($deliveryError)
                            <div class="text-red-500 text-sm mt-1">{{ $deliveryError }}</div>
                        @endif
                        <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                            <span>Total</span>
                            <span>₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button wire:click="proceedToReview" class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed" {{ !$isServiceable || !$selectedAddressId ? 'disabled' : '' }}>
                        Proceed to Payment
                    </button>
                </div>
            </div>
        @elseif($currentStep == 2)
            <!-- Review & Payment Step -->
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold mb-8 text-center">Review Your Order</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Order Summary -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4">Order Summary</h3>
                        <div class="space-y-3 mb-4">
                            @foreach($cartItems as $item)
                            <div class="flex justify-between text-sm">
                                <span>
                                    {{ $item->product->name }} 
                                    @if($item->productVariant)
                                        ({{ $item->productVariant->name }})
                                    @endif
                                    x {{ $item->quantity }}
                                </span>
                                <span>₹{{ number_format(($item->productVariant ? $item->productVariant->price : $item->product->effective_price) * $item->quantity, 2) }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="border-t pt-3 space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>₹{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Delivery Fee</span>
                                <span>₹{{ number_format($deliveryFee, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg border-t pt-2 mt-2">
                                <span>Total</span>
                                <span>₹{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4">Delivery Address</h3>
                        @php $addr = $addresses->find($selectedAddressId); @endphp
                        <div class="text-gray-700">
                            <p class="font-bold">{{ $addr->name }}</p>
                            <p class="mt-2">{{ $addr->address_line1 }}</p>
                            @if($addr->address_line2)
                                <p>{{ $addr->address_line2 }}</p>
                            @endif
                            <p>{{ $addr->city }}, {{ $addr->state }} - {{ $addr->pincode }}</p>
                            <p class="mt-2">Phone: {{ $addr->phone }}</p>
                        </div>
                        <button wire:click="$set('currentStep', 1)" class="mt-4 text-blue-600 underline text-sm">Change Address</button>
                    </div>
                </div>

                <!-- Payment Button -->
                <div class="mt-8 text-center">
                    <button wire:click="initiatePayment" type="button" class="bg-green-600 text-white px-12 py-4 rounded-lg font-bold text-lg hover:bg-green-700 shadow-lg transition">
                        Pay ₹{{ number_format($total, 2) }}
                    </button>
                    <div id="payment-error" class="text-red-500 mt-3"></div>
                    <p class="text-gray-500 text-sm mt-3">🔒 Secure payment via Razorpay</p>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Listen for payment data from backend
            Livewire.on('open-razorpay-modal', (data) => {
                console.log('Opening Razorpay modal with data:', data);
                
                // Handle potentially different data structures (array vs object)
                const paymentData = Array.isArray(data) ? data[0] : data;

                if (!paymentData || !paymentData.key) {
                    console.error('Invalid payment data received:', data);
                    alert('Unable to initialize payment. Please try again.');
                    return;
                }
                
                const options = {
                    "key": paymentData.key,
                    "amount": paymentData.amount,
                    "currency": "INR",
                    "name": paymentData.name,
                    "description": paymentData.description,
                    "order_id": paymentData.razorpay_order_id,
                    "handler": function (response){
                        console.log('Payment successful:', response);
                        @this.handlePaymentSuccess({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature
                        });
                    },
                    "prefill": {
                        "name": paymentData.prefill.name,
                        "email": paymentData.prefill.email,
                        "contact": paymentData.prefill.contact
                    },
                    "theme": {
                        "color": "#16a34a"
                    },
                    "modal": {
                        "ondismiss": function(){
                            console.log('Razorpay modal closed');
                        }
                    }
                };
                
                const rzp1 = new Razorpay(options);
                rzp1.on('payment.failed', function (response){
                    console.error('Payment failed:', response);
                    alert(response.error.description);
                });
                rzp1.open();
            });
        });
    </script>
    @endpush
</div>
