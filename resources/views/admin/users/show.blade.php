@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('header', 'Detail Pengguna')

@section('content')
<div class="space-y-6">
    <!-- User Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-user text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Informasi Pengguna</h2>
                    <p class="text-sm text-gray-500">Detail lengkap pengguna</p>
                </div>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-edit mr-2"></i>
                Edit Pengguna
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                <div>
                    <div class="flex items-center space-x-4">
                        <img class="h-16 w-16 rounded-full" 
                             src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" 
                             alt="{{ $user->name }}">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nomor Telepon</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Alamat</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-2">Statistik Pesanan</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->orders->count() }}</p>
                            <p class="text-sm text-gray-500">Total Pesanan</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($user->orders->sum('total_amount'), 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-gray-500">Total Pembelian</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Riwayat Pesanan</h3>
                    <p class="text-sm text-gray-500">Daftar pesanan yang pernah dibuat</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($user->orders as $order)
            <div class="p-6 hover:bg-gray-50 transition duration-150">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   'bg-red-100 text-red-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                Order #{{ $order->order_number }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </p>
                        <a href="{{ route('admin.orders.show', $order) }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Lihat Detail
                        </a>
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

    <!-- Back Button -->
    <div class="flex justify-end">
        <a href="{{ route('admin.users') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pengguna
        </a>
    </div>
</div>
@endsection 