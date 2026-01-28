@extends('layouts.app')

@section('title', 'My Account')

@section('content')
<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    @if(!auth()->user()->hasVerifiedEmail())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Please verify your email address.</strong> 
                        We sent a verification link to <strong>{{ auth()->user()->email }}</strong>. 
                        <a href="{{ route('verification.notice') }}" class="font-medium underline hover:text-yellow-600">
                            Click here to resend
                        </a>
                    </p>
                </div>
            </div>
        </div>
    @endif

    <h1 class="text-3xl font-bold mb-8">My Account</h1>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <nav class="space-y-2">
                <a href="#" class="block px-4 py-2 text-blue-600 bg-blue-50 rounded-lg font-medium">Dashboard</a>
                <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Orders</a>
                <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Addresses</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Account Details</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                        Logout
                    </button>
                </form>
            </nav>
        </div>

        <div class="md:col-span-3">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold mb-4">Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-600">Manage your orders, addresses, and account settings from your dashboard.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                    <div class="border rounded-lg p-4">
                        <div class="text-3xl mb-2">📦</div>
                        <div class="text-2xl font-bold">{{ $totalOrders }}</div>
                        <div class="text-sm text-gray-600">Total Orders</div>
                    </div>
                    <div class="border rounded-lg p-4">
                        <div class="text-3xl mb-2">🚚</div>
                        <div class="text-2xl font-bold">{{ $pendingOrders }}</div>
                        <div class="text-sm text-gray-600">Pending Orders</div>
                    </div>
                    <div class="border rounded-lg p-4">
                        <div class="text-3xl mb-2">✅</div>
                        <div class="text-2xl font-bold">{{ $completedOrders }}</div>
                        <div class="text-sm text-gray-600">Completed Orders</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
