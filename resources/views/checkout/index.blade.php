@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-white border-b">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
        <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
    </div>
</div>

<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    @livewire('checkout')
</div>
@endsection
