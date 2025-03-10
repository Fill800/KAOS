@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('header', 'Detail Pesanan')

@section('content')
<div class="space-y-6">
    <!-- Order Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Pesanan #{{ $order->order_number }}</h2>
                    <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="flex items-center space-x-4">
                @csrf
                @method('PUT')
                <select name="status" 
                        class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                        onchange="this.form.submit()">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <span class="px-3 py-1 text-xs font-medium rounded-full
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                       ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                       ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                       'bg-red-100 text-red-800')) }}">
                    {{ ucfirst($order->status) }}
                </span>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Customer Info -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Informasi Pelanggan</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center space-x-4">
                        <img class="h-10 w-10 rounded-full" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($order->user->name) }}&color=7F9CF5&background=EBF4FF" 
                             alt="{{ $order->user->name }}">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                            <p class="text-sm text-gray-500 mt-1">{{ $order->shipping_address }}</p>
                            <p class="text-sm text-gray-500">{{ $order->phone_number }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-2">Ringkasan Pesanan</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Item</span>
                            <span class="font-medium text-gray-900">{{ $order->orderItems->count() }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Total Pembayaran</span>
                            <span class="font-medium text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Metode Pembayaran</span>
                            <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Item Pesanan</h3>
                    <p class="text-sm text-gray-500">Daftar item dalam pesanan ini</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($order->orderItems as $item)
            <div class="p-6 hover:bg-gray-50 transition duration-150">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 w-16 h-16">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 class="w-16 h-16 object-cover rounded-lg"
                                 alt="{{ $item->product->name }}">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $item->product->name }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                        </p>
                        @if($item->customization)
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-paint-brush mr-1"></i> Custom
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-end">
        <a href="{{ route('admin.orders') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pesanan
        </a>
    </div>
</div>
@endsection 