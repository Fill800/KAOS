@extends('layouts.user')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="space-y-6">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1472851294608-062f824d29cc?ixlib=rb-4.0.3" 
                 class="w-full h-full object-cover opacity-20" alt="Cart Banner">
        </div>
        <div class="relative px-8 py-12 sm:px-12 sm:py-16">
            <div class="space-y-4 max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-bold text-white">
                    Keranjang Belanja
                </h1>
                <p class="text-blue-100 text-lg">
                    Kelola item belanjaan Anda dan lanjutkan ke pembayaran
                </p>
            </div>
        </div>
    </div>

    @if($cartItems->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cart Items List -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Cart Summary Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Item Belanja ({{ $cartItems->count() }})</h3>
                        <p class="text-sm text-gray-500">Kelola item di keranjang Anda</p>
                    </div>
                </div>
            </div>

            @foreach($cartItems as $item)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:border-blue-500 transition duration-150">
                <div class="flex items-center space-x-4">
                    <!-- Product Image -->
                    <div class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" 
                                 class="w-full h-full object-cover"
                                 alt="{{ $item->product->name }}">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-tshirt text-3xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $item->product->name }}
                        </h3>
                        @if($item->customization)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                <i class="fas fa-paint-brush mr-1"></i> Custom
                            </span>
                        @endif
                        <div class="mt-4 flex items-center space-x-4">
                            <!-- Quantity Selector -->
                            <div class="flex items-center space-x-2">
                                <button onclick="updateQuantity({{ $item->id }}, 'decrease')" 
                                        class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" value="{{ $item->quantity }}" 
                                       class="w-16 text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       min="1" 
                                       onchange="updateQuantity({{ $item->id }}, 'set', this.value)">
                                <button onclick="updateQuantity({{ $item->id }}, 'increase')"
                                        class="p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <!-- Price -->
                            <div class="text-right flex-1">
                                <p class="text-sm text-gray-500">Harga per item</p>
                                <p class="text-lg font-bold text-gray-900">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <!-- Remove Button -->
                            <form action="{{ route('cart.remove') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                <button type="submit" 
                                        class="p-2 text-gray-400 hover:text-red-500 transition duration-150"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-receipt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Ringkasan Pesanan</h3>
                        <p class="text-sm text-gray-500">Detail total pembayaran</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal ({{ $cartItems->count() }} item)</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between text-gray-600">
                        <span>Biaya Pengiriman</span>
                        <span class="text-green-600">Gratis</span>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" 
                       class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition duration-150">
                        Lanjut ke Pembayaran
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>

                    <!-- Additional Info -->
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-truck mr-2"></i>
                            <span>Pengiriman gratis untuk semua pesanan</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Pembayaran aman & terenkripsi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
    <!-- Empty Cart State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="max-w-sm mx-auto">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-6">
                <i class="fas fa-shopping-cart text-2xl text-blue-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Keranjang Belanja Kosong</h3>
            <p class="text-gray-500 mb-6">
                Anda belum menambahkan produk apapun ke keranjang belanja.
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
@endsection
@push('scripts')
<script>
function updateQuantity(cartId, action, value = null) {
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('cart.update') }}';

    let tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = '{{ csrf_token() }}';
    form.appendChild(tokenInput);

    let methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'PUT';
    form.appendChild(methodInput);

    let cartIdInput = document.createElement('input');
    cartIdInput.type = 'hidden';
    cartIdInput.name = 'cart_id';
    cartIdInput.value = cartId;
    form.appendChild(cartIdInput);

    let quantityInput = document.createElement('input');
    quantityInput.type = 'hidden';
    quantityInput.name = 'quantity';
    
    switch(action) {
        case 'increase':
            quantityInput.value = parseInt(document.querySelector(`input[value="${cartId}"]`).closest('.flex').querySelector('input[type="number"]').value) + 1;
            break;
        case 'decrease':
            let currentQty = parseInt(document.querySelector(`input[value="${cartId}"]`).closest('.flex').querySelector('input[type="number"]').value);
            if (currentQty > 1) {
                quantityInput.value = currentQty - 1;
            } else {
                return;
            }
            break;
        case 'set':
            quantityInput.value = parseInt(value);
            if (quantityInput.value < 1) {
                quantityInput.value = 1;
            }
            break;
    }

    form.appendChild(quantityInput);
    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush 