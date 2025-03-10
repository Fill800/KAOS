@extends('layouts.user')

@section('title', 'Katalog Produk')

@section('content')
<div class="space-y-6">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1529374255404-311a2a4f1fd9?ixlib=rb-4.0.3" 
                 class="w-full h-full object-cover opacity-20" alt="Catalog Banner">
        </div>
        <div class="relative px-8 py-12 sm:px-12 sm:py-16">
            <div class="space-y-4 max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-bold text-white">
                    Katalog Produk Custom
                </h1>
                <p class="text-blue-100 text-lg">
                    Pilih produk favoritmu dan custom sesuai keinginanmu. Tersedia berbagai pilihan produk berkualitas.
                </p>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <!-- Search -->
            <div class="relative flex-1 max-w-lg">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Cari produk...">
            </div>

            <!-- Filters -->
            <div class="flex items-center space-x-4">
                <select class="form-select rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    <option value="kaos">Kaos</option>
                    <option value="hoodie">Hoodie</option>
                    <option value="totebag">Tote Bag</option>
                </select>

                <select class="form-select rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest">Terbaru</option>
                    <option value="price_low">Harga: Rendah ke Tinggi</option>
                    <option value="price_high">Harga: Tinggi ke Rendah</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
        <div class="group bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300">
            <!-- Product Image -->
            <div class="relative aspect-w-4 aspect-h-3">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300" 
                         alt="{{ $product->name }}">
                @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-tshirt text-4xl text-gray-400"></i>
                    </div>
                @endif
                
                <!-- Quick Actions -->
                <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center space-x-4">
                    <form action="{{ route('cart.add') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" 
                                class="p-2 bg-white rounded-full hover:bg-blue-600 hover:text-white transition duration-150"
                                title="Tambah ke Keranjang">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <h3 class="font-medium text-gray-900 group-hover:text-blue-600 transition duration-150">
                    {{ $product->name }}
                </h3>
                <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                    {{ $product->description }}
                </p>
                <div class="mt-3 flex items-center justify-between">
                    <span class="text-lg font-bold text-gray-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm text-gray-500">
                        Stok: {{ $product->stock }}
                    </span>
                </div>
            </div>

            <!-- Product Category Badge -->
            <div class="absolute top-4 left-4">
                <span class="px-3 py-1 text-xs font-medium bg-white rounded-full shadow-md">
                    {{ ucfirst($product->category) }}
                </span>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="bg-gray-50 rounded-xl p-8 max-w-lg mx-auto">
                <i class="fas fa-box-open text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada produk</h3>
                <p class="text-gray-500">Maaf, tidak ada produk yang tersedia saat ini.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        {{ $products->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .aspect-w-4 {
        position: relative;
        padding-bottom: 75%;
    }
    .aspect-h-3 {
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>
@endpush
@endsection 