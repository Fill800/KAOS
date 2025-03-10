@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Hero Banner -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1558769132-cb1aea458c5e?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1740&q=80" 
                 class="w-full h-full object-cover opacity-20" alt="Banner Background">
        </div>
        <div class="relative px-8 py-12 sm:px-12 sm:py-16">
            <div class="space-y-4">
                <h1 class="text-3xl sm:text-4xl font-bold text-white">
                    Selamat Datang, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-blue-100 text-lg max-w-2xl">
                    Buat desain custom kamu sendiri dan dapatkan produk fashion yang unik sesuai keinginanmu.
                </p>
                <div class="pt-4">
                    <a href="{{ route('user.catalog') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-blue-700 bg-white hover:bg-blue-50 transition duration-150">
                        Mulai Desain
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-bag text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pesanan</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $orders->count() }}</h3>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pesanan Selesai</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ $orders->where('status', 'completed')->count() }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pesanan Pending</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ $orders->where('status', 'pending')->count() }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Pesanan Terakhir</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($orders as $order)
            <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition duration-150">
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
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $order->order_type }}</p>
                    </div>
                    <a href="{{ route('user.order.detail', $order->id) }}" 
                       class="p-2 text-gray-400 hover:text-gray-600 transition duration-150">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                <p>Belum ada pesanan.</p>
                <a href="{{ route('user.catalog') }}" class="text-blue-600 hover:text-blue-800 font-medium mt-2 inline-block">
                    Mulai Belanja
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="{{ route('user.catalog') }}" 
           class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-blue-500 transition duration-150">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition duration-150">
                    <i class="fas fa-tshirt text-blue-600 group-hover:text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Katalog Produk</h3>
                    <p class="text-sm text-gray-500">Lihat semua produk</p>
                </div>
            </div>
        </a>

        <a href="{{ route('user.cart') }}" 
           class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-blue-500 transition duration-150">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition duration-150">
                    <i class="fas fa-shopping-cart text-blue-600 group-hover:text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Keranjang</h3>
                    <p class="text-sm text-gray-500">Lihat keranjang belanja</p>
                </div>
            </div>
        </a>

        <a href="{{ route('user.orders') }}" 
           class="group bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-blue-500 transition duration-150">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg group-hover:bg-blue-600 transition duration-150">
                    <i class="fas fa-list-alt text-blue-600 group-hover:text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Pesanan</h3>
                    <p class="text-sm text-gray-500">Lihat semua pesanan</p>
                </div>
            </div>
        </a>

        <div class="group bg-gradient-to-r from-blue-600 to-blue-800 p-6 rounded-xl shadow-sm text-white">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-white/20 rounded-lg">
                    <i class="fas fa-headset text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium">Butuh Bantuan?</h3>
                    <p class="text-sm text-blue-100">Hubungi customer service</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 