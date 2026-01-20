@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="bg-white border-b">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
        <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
    </div>
</div>

<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    @livewire('shopping-cart')
</div>
@endsection
