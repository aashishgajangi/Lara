<div>
    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($isEditing)
        {{-- Edit Address Form with Map --}}
        <div class="bg-white border-2 border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6" 
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
                    const lat = {{ $editAddress['lat'] ?? '20.5937' }};
                    const lng = {{ $editAddress['lng'] ?? '78.9629' }};
                    const defaultLocation = { lat: lat, lng: lng };
                    
                    this.map = new google.maps.Map(document.getElementById('edit-map'), {
                        center: defaultLocation,
                        zoom: {{ $editAddress['lat'] ? '15' : '5' }},
                        disableDefaultUI: true,
                        zoomControl: false,
                        streetViewControl: false,
                        gestureHandling: 'greedy'
                    });

                    this.geocoder = new google.maps.Geocoder();

                    this.map.addListener('dragstart', () => {
                        this.isDragging = true;
                    });

                    this.map.addListener('idle', () => {
                        this.isDragging = false;
                        const center = this.map.getCenter();
                        this.geocodePosition(center);
                    });

                    this.initAutocomplete();
                },
                geocodePosition(location) {
                    this.geocoder.geocode({ location: location }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            this.fillInAddress(results[0]);
                        }
                    });
                },
                initAutocomplete() {
                    const input = document.getElementById('edit-address-input');
                    const autocomplete = new google.maps.places.Autocomplete(input, {
                        componentRestrictions: { country: 'in' }
                    });

                    autocomplete.addListener('place_changed', () => {
                        const place = autocomplete.getPlace();
                        if (!place.geometry || !place.geometry.location) return;
                        
                        this.map.setCenter(place.geometry.location);
                        this.map.setZoom(15);
                        this.fillInAddress(place);
                    });
                },
                useCurrentLocation() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                const pos = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude,
                                };
                                this.map.setCenter(pos);
                                this.map.setZoom(15);
                                this.geocodePosition(pos);
                            },
                            () => alert('Error: Unable to retrieve your location.')
                        );
                    }
                },
                fillInAddress(place) {
                    let street = '', city = '', state = '', pincode = '';

                    const components = place.address_components;
                    for (const component of components) {
                        const types = component.types;
                        if (types.includes('route') || types.includes('street_number')) {
                            street += component.long_name + ' ';
                        }
                        if (types.includes('locality') || types.includes('administrative_area_level_2')) {
                            city = component.long_name;
                        }
                        if (types.includes('administrative_area_level_1')) {
                            state = component.short_name;
                        }
                        if (types.includes('postal_code')) {
                            pincode = component.long_name;
                        }
                    }

                    @this.set('editAddress.address_line1', street.trim() || place.formatted_address);
                    @this.set('editAddress.city', city);
                    @this.set('editAddress.state', state);
                    @this.set('editAddress.pincode', pincode);
                    @this.set('editAddress.lat', typeof place.geometry.location.lat === 'function' ? place.geometry.location.lat() : place.geometry.location.lat);
                    @this.set('editAddress.lng', typeof place.geometry.location.lng === 'function' ? place.geometry.location.lng() : place.geometry.location.lng);
                }
            }"
            x-init="initMap()">

            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Edit Address</h3>
                    <button wire:click="cancelEdit" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Search & Locate --}}
                <div wire:ignore class="mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="edit-address-input" placeholder="Search for area, street name..." class="pl-10 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500 py-3">
                </div>

                @error('general')
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ $message }}</span>
                    </div>
                @enderror

                {{-- Map Container --}}
                <div wire:ignore class="relative mb-8 rounded-xl overflow-hidden shadow-lg border border-gray-200 group">
                    <div id="edit-map" class="w-full bg-gray-100" style="height: 320px;"></div>
                    
                    {{-- Center Pin --}}
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; z-index: 20; padding-bottom: 38px;">
                        <svg height="40px" width="40px" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#ef4444">
                            <path style="fill:#EF4444;" d="M256,0C153.755,0,70.573,83.182,70.573,185.426c0,126.888,165.939,313.167,173.004,321.035
                                c6.636,7.391,18.222,7.378,24.846,0c7.065-7.868,173.004-194.147,173.004-321.035C441.425,83.182,358.244,0,256,0z M256,278.719
                                c-51.442,0-93.292-41.851-93.292-93.293S204.559,92.134,256,92.134s93.291,41.851,93.291,93.293S307.441,278.719,256,278.719z"/>
                        </svg>
                        <div style="position: absolute; bottom: 38px; left: 50%; transform: translateX(-50%); width: 8px; height: 8px; background: #ef4444; border-radius: 9999px; animation: pulse 2s infinite; opacity: 0.75;"></div>
                    </div>

                    {{-- Tooltip --}}
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -80px); z-index: 10; pointer-events: none; transition: opacity 0.3s;"
                         :style="{ opacity: isDragging ? 0 : 1 }">
                        <div style="background: #1f2937; color: white; font-size: 0.875rem; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 500; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); white-space: nowrap; position: relative;">
                            Move pin to your exact delivery location
                            <div style="position: absolute; bottom: -4px; left: 50%; transform: translateX(-50%); width: 12px; height: 12px; background: #1f2937; transform: translateX(-50%) rotate(45deg);"></div>
                        </div>
                    </div>

                    {{-- Use Current Location Button --}}
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-10 w-full max-w-xs px-4">
                        <button @click="useCurrentLocation()" type="button" class="w-full bg-white hover:bg-gray-50 text-gray-700 font-medium py-2.5 px-4 rounded-lg shadow-md border border-gray-200 flex items-center justify-center gap-2 transition-all">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Use current location
                        </button>
                    </div>
                </div>

                {{-- Form Fields --}}
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="editAddress.name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        @error('editAddress.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="tel" wire:model="editAddress.phone" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        @error('editAddress.phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="editAddress.address_line1" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        @error('editAddress.address_line1') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                        <input type="text" wire:model="editAddress.address_line2" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="editAddress.city" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            @error('editAddress.city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="editAddress.state" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                            @error('editAddress.state') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pincode <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="editAddress.pincode" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        @error('editAddress.pincode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Type</label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="editAddress.type" value="home" class="form-radio text-primary-600">
                                <span class="ml-2">Home</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="editAddress.type" value="office" class="form-radio text-primary-600">
                                <span class="ml-2">Office</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="editAddress.type" value="other" class="form-radio text-primary-600">
                                <span class="ml-2">Other</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" wire:model="editAddress.is_default" id="edit-default" class="form-checkbox text-primary-600">
                        <label for="edit-default" class="ml-2 text-sm text-gray-700">Set as default address</label>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button wire:click="updateAddress" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                        Save Changes
                    </button>
                    <button wire:click="cancelEdit" class="px-6 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 rounded-lg transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- Address List --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold mb-4">My Addresses</h2>
            
            @if ($addresses->isEmpty())
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No addresses saved</h3>
                    <p class="text-gray-500 mb-4">Add your first address to get started with deliveries</p>
                    <a href="{{ route('checkout') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors">
                        Add Address in Checkout
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($addresses as $address)
                        <div class="bg-white rounded-lg shadow-sm border-2 {{ $address->is_default ? 'border-blue-500' : 'border-gray-200' }} p-4 relative">
                            @if ($address->is_default)
                                <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Default</span>
                            @endif

                            <div class="mb-2">
                                <span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded uppercase font-semibold">
                                    {{ ucfirst($address->type) }}
                                </span>
                            </div>

                            <h4 class="font-bold text-lg">{{ $address->name }}</h4>
                            <p class="text-gray-600 text-sm mt-1">{{ $address->phone }}</p>
                            <p class="text-gray-800 mt-2">
                                {{ $address->address_line1 }}
                                @if($address->address_line2), {{ $address->address_line2 }}@endif
                            </p>
                            <p class="text-gray-600 text-sm">
                                {{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}
                            </p>

                            <div class="mt-4 flex gap-2">
                                <button wire:click="startEdit({{ $address->id }})" class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium py-2 px-4 rounded-lg transition-colors">
                                    Edit
                                </button>
                                @if (!$address->is_default)
                                    <button wire:click="setAsDefault({{ $address->id }})" class="flex-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-medium py-2 px-4 rounded-lg transition-colors">
                                        Set Default
                                    </button>
                                @endif
                                <button 
                                    x-data
                                    @click="if(confirm('Are you sure you want to delete this address?')) { $wire.deleteAddress({{ $address->id }}) }"
                                    class="bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 px-4 rounded-lg transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    @push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
    @endpush
</div>
