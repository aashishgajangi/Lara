@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center">
        <div class="mb-4">
            <svg class="mx-auto h-24 w-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Order Placed Successfully!</h1>
        <p class="text-xl text-gray-600 mb-8">Thank you for your purchase. Your order has been received.</p>
        
        <div class="bg-gray-50 rounded-lg p-8 max-w-lg mx-auto mb-8 text-left">
            <div class="flex justify-between mb-4">
                <span class="text-gray-600">Order Number:</span>
                <span class="font-bold text-gray-900">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between mb-4">
                <span class="text-gray-600">Date:</span>
                <span class="font-bold text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between mb-4 border-t pt-4">
                <span class="text-gray-600">Total Amount:</span>
                <span class="font-extrabold text-xl text-green-600">₹{{ number_format($order->total, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Payment Status:</span>
                 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $order->payment_status }}
                </span>
            </div>
        </div>

        <div class="space-x-4">
            <a href="{{ route('home') }}" class="inline-block bg-primary-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-primary-700 transition">
                Continue Shopping
            </a>
            <a href="{{ route('account.dashboard') }}" class="inline-block bg-white text-primary-600 border-2 border-primary-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-50 transition">
                View Orders
            </a>
        </div>
    </div>
</div>
@endsection
