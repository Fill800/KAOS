@extends('layouts.user')

@section('title', 'Pesanan Saya')

@section('content')
<div class="space-y-6">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3" 
                 class="w-full h-full object-cover opacity-20" alt="Orders Banner">
        </div>
        <div class="relative px-8 py-12 sm:px-12 sm:py-16">
            <div class="space-y-4 max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-bold text-white">
                    Pesanan Saya
                </h1>
                <p class="text-blue-100 text-lg">
                    Pantau dan kelola semua pesanan Anda di satu tempat
                </p>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-list-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Daftar Pesanan</h3>
                    <p class="text-sm text-gray-500">Semua riwayat pesanan Anda</p>
                </div>
            </div>
        </div>

        @if($orders->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($orders as $order)
                <div class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                        <!-- Order Info -->
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @switch($order->status)
                                    @case('pending')
                                        <span class="px-3 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full">
                                            Pending
                                        </span>
                                        @break
                                    @case('processing')
                                        <span class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">
                                            Diproses
                                        </span>
                                        @break
                                    @case('completed')
                                        <span class="px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                            Selesai
                                        </span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                @endswitch
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Order #{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="flex items-center space-x-6">
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    <i class="fas {{ $order->order_type == 'custom' ? 'fa-paint-brush' : 'fa-shopping-bag' }} mr-1"></i>
                                    {{ ucfirst($order->order_type) }}
                                </p>
                            </div>
                            <a href="{{ route('user.order.detail', $order->id) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                                Lihat Detail
                                <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="p-12 text-center">
                <div class="max-w-sm mx-auto">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-6">
                        <i class="fas fa-shopping-bag text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 mb-6">
                        Anda belum memiliki pesanan apapun. Mulai belanja sekarang!
                    </p>
                    <a href="{{ route('user.catalog') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                        Mulai Belanja
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 