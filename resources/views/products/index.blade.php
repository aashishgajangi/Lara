@extends('layouts.app')

@section('title', 'All Products - Shop Now')

@section('content')
<div class="bg-white border-b">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
        <h1 class="text-3xl font-bold text-gray-900">All Products</h1>
        <p class="text-gray-600 mt-2">Browse our complete collection</p>
    </div>
</div>

<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    @livewire('product-listing')
</div>
@endsection
