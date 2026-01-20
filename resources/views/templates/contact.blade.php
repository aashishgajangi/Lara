@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('content')
@php
    $containerClass = \App\Helpers\LayoutHelper::getContainerClasses();
@endphp

{{-- PAGE HEADER --}}
@if(($page->sections['hero'] ?? false) && !empty($page->section_data['hero']))
    @php
        $hero = $page->section_data['hero'];
    @endphp
    <div class="bg-gradient-to-r from-primary-600 to-secondary-600 text-white py-16">
        <div class="{{ $containerClass }} text-center">
            <h1 class="text-4xl md:text-5xl font-bold">{{ $hero['title'] ?? $page->title }}</h1>
        </div>
    </div>
@endif

<div class="{{ $containerClass }} py-16">
    <div class="grid md:grid-cols-2 gap-12">
        {{-- CONTACT FORM --}}
        @if($page->sections['form'] ?? false)
            <div>
                <h2 class="text-2xl font-bold mb-6">Send us a Message</h2>
                <livewire:contact-form />
            </div>
        @endif

        {{-- CONTACT INFO --}}
        @if(($page->sections['info'] ?? false) && !empty($page->section_data['info']))
            @php
                $info = $page->section_data['info'];
            @endphp
            <div>
                <h2 class="text-2xl font-bold mb-6">Contact Information</h2>
                <div class="space-y-4">
                    @if(!empty($info['address']))
                        <div class="flex items-start">
                            <div class="text-blue-600 mr-4 text-xl">📍</div>
                            <div>
                                <h3 class="font-semibold mb-1">Address</h3>
                                <p class="text-gray-600">{{ $info['address'] }}</p>
                            </div>
                        </div>
                    @endif
                    @if(!empty($info['phone']))
                        <div class="flex items-start">
                            <div class="text-blue-600 mr-4 text-xl">📞</div>
                            <div>
                                <h3 class="font-semibold mb-1">Phone</h3>
                                <p class="text-gray-600">{{ $info['phone'] }}</p>
                            </div>
                        </div>
                    @endif
                    @if(!empty($info['email']))
                        <div class="flex items-start">
                            <div class="text-blue-600 mr-4 text-xl">📧</div>
                            <div>
                                <h3 class="font-semibold mb-1">Email</h3>
                                <p class="text-gray-600">{{ $info['email'] }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- MAP SECTION --}}
    @if(($page->sections['map'] ?? false) && !empty($page->section_data['map']['embed_code']))
        <div class="mt-12">
            <div class="rounded-lg overflow-hidden shadow-lg">
                {!! $page->section_data['map']['embed_code'] !!}
            </div>
        </div>
    @endif
</div>

@endsection
