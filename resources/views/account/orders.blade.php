@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="{{ \App\Helpers\LayoutHelper::getContainerClasses() }} py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <div class="bg-white rounded-lg shadow-sm p-6 h-fit">
            <nav class="space-y-2">
                <a href="{{ route('account.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 rounded-lg">Dashboard</a>
                <a href="{{ route('account.orders') }}" class="block px-4 py-2 text-blue-600 bg-blue-50 rounded-lg font-medium">Orders</a>
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
                <h1 class="text-2xl font-bold mb-6">My Orders</h1>

                @if($orders->isEmpty())
                    <div class="text-center py-12">
                        <div class="text-4xl mb-4">📦</div>
                        <h3 class="text-lg font-medium text-gray-900">No orders yet</h3>
                        <p class="mt-1 text-gray-500">You haven't placed any orders yet.</p>
                        <div class="mt-6">
                            <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Start Shopping
                            </a>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Order #
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $order->order_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                                   ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ₹{{ number_format($order->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('orders.success', $order->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
