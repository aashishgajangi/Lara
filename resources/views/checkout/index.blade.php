@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="bg-white border-b">
    <div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
        <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
    </div>
</div>

<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <div class="text-6xl mb-4">🚧</div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Checkout Under Construction</h2>
        <p class="text-gray-600 mb-6">This feature is coming soon.</p>
        <a href="{{ route('cart.index') }}" 
           class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
            Return to Cart
        </a>
    </div>
</div>
@endsection
