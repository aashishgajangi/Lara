@extends('layouts.app')

@section('title', $page->title)

@section('content')
    {{-- Page Title (Optional) --}}
    @if($page->show_title)
        <div class="bg-white shadow">
            <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-6">
                <h1 class="text-3xl font-bold text-gray-900">{{ $page->title }}</h1>
            </div>
        </div>
    @endif

    {{-- Blocks Loop --}}
    <div class="flex flex-col">
        @if(is_array($page->blocks))
            @foreach($page->blocks as $block)
                @php
                    $type = $block['type'];
                    $data = $block['data'];
                @endphp

                @switch($type)
                    @case('hero')
                        <x-blocks.hero-builder :data="$data" />
                        @break

                    @case('text')
                        <section class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-12">
                            <div class="prose max-w-none">
                                {!! $data['content'] !!}
                            </div>
                        </section>
                        @break

                    @case('image')
                        <section class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-12">
                            <figure>
                                <img src="{{ \App\Helpers\MediaHelper::resolveUrl($data['url']) }}" 
                                     alt="{{ $data['alt'] }}" 
                                     class="w-full h-auto rounded-lg shadow-md">
                                @if(!empty($data['caption']))
                                    <figcaption class="mt-2 text-center text-sm text-gray-500">{{ $data['caption'] }}</figcaption>
                                @endif
                            </figure>
                        </section>
                        @break

                    @case('product_grid')
                        <section class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-12">
                            @if(!empty($data['heading']))
                                <h2 class="text-2xl font-bold mb-6">{{ $data['heading'] }}</h2>
                            @endif
                            @livewire('product-grid-block', [
                                'type' => $data['type'],
                                'count' => $data['count']
                            ])
                        </section>
                        @break

                    @case('html')
                        <div class="w-full">
                            {!! $data['code'] !!}
                        </div>
                        @break
                @endswitch
            @endforeach
        @endif
    </div>
@endsection
