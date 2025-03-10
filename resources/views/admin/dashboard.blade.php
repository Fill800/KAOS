@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Pesanan -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pesanan</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>{{ $orderGrowth }}% dari bulan lalu</span>
            </div>
        </div>

        <!-- Pendapatan -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-green-600">
                <i class="fas fa-arrow-up mr-1"></i>
                <span>{{ $revenueGrowth }}% dari bulan lalu</span>
            </div>
        </div>

        <!-- Total Produk -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Produk</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-yellow-600">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                <span>{{ $lowStockProducts }} produk stok menipis</span>
            </div>
        </div>

        <!-- Total Pengguna -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-users text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Pengguna</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</h3>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-blue-600">
                <i class="fas fa-user-plus mr-1"></i>
                <span>{{ $newUsers }} pengguna baru bulan ini</span>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i class="fas fa-clock text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Pesanan Terbaru</h3>
                            <p class="text-sm text-gray-500">10 pesanan terakhir</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.orders') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentOrders as $order)
                <div class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-xs font-medium 
                                {{ $order->status === 'pending' ? 'text-yellow-700 bg-yellow-100' : 
                                   ($order->status === 'processing' ? 'text-blue-700 bg-blue-100' : 
                                   'text-green-700 bg-green-100') }} rounded-full">
                                {{ ucfirst($order->status) }}
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Order #{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $order->user->name }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    Belum ada pesanan
                </div>
                @endforelse
            </div>
        </div>

        <!-- Popular Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Produk Terpopuler</h3>
                            <p class="text-sm text-gray-500">Berdasarkan penjualan</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.products') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($popularProducts as $product)
                <div class="p-6 hover:bg-gray-50 transition duration-150">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-12 h-12">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     class="w-full h-full object-cover rounded-lg"
                                     alt="{{ $product->name }}">
                            @else
                                <div class="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $product->name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $product->total_sales }} terjual
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Stok: {{ $product->stock }}
                            </p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    Belum ada data produk
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 