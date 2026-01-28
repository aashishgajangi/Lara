@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        {{-- Sidebar --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <nav class="space-y-2">
                <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Dashboard</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Orders</a>
                <a href="{{ route('account.addresses') }}" class="block px-4 py-2 text-blue-600 bg-blue-50 rounded-lg font-medium">Addresses</a>
                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Account Details</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg">
                        Logout
                    </button>
                </form>
            </nav>
        </div>

        {{-- Main Content --}}
        <div class="md:col-span-3">
            @livewire('address-manager')
        </div>
    </div>
</div>
@endsection
