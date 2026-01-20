@props(['data'])
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp
<section class="py-16 px-4 bg-gray-50">
    <div class="{{ $containerClass }}">
        @if(!empty($data['heading']))
            <h2 class="text-3xl font-bold text-center mb-12 font-heading text-gray-900">{{ $data['heading'] }}</h2>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            {{-- Contact Info --}}
            <div>
                <div class="bg-white rounded-lg shadow-sm p-8 h-full">
                    <h3 class="text-xl font-bold mb-6 text-gray-900">Get in Touch</h3>
                    
                    <div class="space-y-6">
                        @if(!empty($data['address']))
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Address</h4>
                                <p class="mt-1 text-gray-600 whitespace-pre-line">{{ $data['address'] }}</p>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data['phone']))
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Phone</h4>
                                <p class="mt-1 text-gray-600">{{ $data['phone'] }}</p>
                            </div>
                        </div>
                        @endif

                        @if(!empty($data['email']))
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Email</h4>
                                <p class="mt-1 text-gray-600">{{ $data['email'] }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            @if(($data['show_form'] ?? true))
            <div>
                <div class="bg-white rounded-lg shadow-sm p-8">
                    <h3 class="text-xl font-bold mb-6 text-gray-900">Send us a Message</h3>
                    @livewire('contact-form')
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
