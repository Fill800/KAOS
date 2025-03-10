@extends('layouts.user')

@section('title', 'Checkout')

@section('content')
<div class="space-y-6">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?ixlib=rb-4.0.3" 
                 class="w-full h-full object-cover opacity-20" alt="Checkout Banner">
        </div>
        <div class="relative px-8 py-12 sm:px-12 sm:py-16">
            <div class="space-y-4 max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-bold text-white">Checkout</h1>
                <p class="text-blue-100 text-lg">Selesaikan pembayaran untuk pesanan Anda</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Checkout -->
        <div class="lg:col-span-2 space-y-6">
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Informasi Pengiriman -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengiriman</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Alamat Pengiriman
                            </label>
                            <textarea name="shipping_address" rows="3" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Masukkan alamat lengkap pengiriman">{{ auth()->user()->address }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nomor Telepon
                            </label>
                            <input type="tel" name="phone_number" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Contoh: 08123456789"
                                value="{{ auth()->user()->phone }}">
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="update_user_info" id="update_user_info" class="h-4 w-4 text-blue-600">
                            <label for="update_user_info" class="ml-2 text-sm text-gray-600">
                                Simpan informasi ini untuk pembelian berikutnya
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Metode Pembayaran</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="transfer" id="transfer" checked
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                            <label for="transfer" class="flex items-center space-x-3">
                                <i class="fas fa-university text-gray-400"></i>
                                <div>
                                    <p class="font-medium text-gray-900">Transfer Bank</p>
                                    <p class="text-sm text-gray-500">Transfer manual ke rekening kami</p>
                                </div>
                            </label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="payment_method" value="ewallet" id="ewallet"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                            <label for="ewallet" class="flex items-center space-x-3">
                                <i class="fas fa-wallet text-gray-400"></i>
                                <div>
                                    <p class="font-medium text-gray-900">E-Wallet</p>
                                    <p class="text-sm text-gray-500">DANA, OVO, GoPay, dll</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" 
                    class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Buat Pesanan
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>
        </div>

        <!-- Ringkasan Pesanan -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Pesanan</h3>
                
                <!-- Daftar Item -->
                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $item)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                     class="w-full h-full object-cover"
                                     alt="{{ $item->product->name }}">
                            @else
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-tshirt text-2xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $item->product->name }}</p>
                            <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Total -->
                <div class="border-t border-gray-100 pt-4 space-y-4">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Biaya Pengiriman</span>
                        <span class="text-green-600">Gratis</span>
                    </div>
                    <div class="flex justify-between items-center text-lg font-bold text-gray-900 pt-4 border-t border-gray-100">
                        <span>Total</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 