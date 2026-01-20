@extends('layouts.app')

@section('content')
    @if(!empty($page->blocks))
        @foreach($page->blocks as $block)
            <x-dynamic-component :component="'blocks.' . $block['type']" :data="$block['data']" />
        @endforeach
    @else
        <div class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold mb-4">{{ $page->title }}</h1>
            <div class="prose max-w-none">
                <p>No content has been added to this page yet.</p>
            </div>
        </div>
    @endif
@endsection
