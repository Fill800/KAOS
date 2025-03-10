@extends('layouts.user')

@section('title', 'Detail Pesanan')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Detail Pesanan</h2>
        
        <!-- Informasi Pesanan -->
        <div class="mb-6">
            <p class="text-gray-600">Nomor Pesanan: {{ $order->order_number }}</p>
            <p class="text-gray-600">Tanggal: {{ $order->created_at->format('d M Y H:i') }}</p>
            <p class="text-gray-600">Status: 
                <span class="font-semibold 
                    @if($order->status == 'pending') text-yellow-600
                    @elseif($order->status == 'paid') text-green-600
                    @elseif($order->status == 'cancelled') text-red-600
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
        </div>

        <!-- Detail Produk -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">Produk yang Dipesan</h3>
            <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->product->name }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->price) }}</td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4">Rp {{ number_format($item->price * $item->quantity) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-semibold">Total:</td>
                            <td class="px-6 py-4 font-semibold">Rp {{ number_format($order->total_amount) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Informasi Pengiriman -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-3">Informasi Pengiriman</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="mb-2"><span class="font-medium">Alamat:</span> {{ $order->shipping_address }}</p>
                <p><span class="font-medium">No. Telepon:</span> {{ $order->phone_number }}</p>
            </div>
        </div>

        <!-- Informasi Pembayaran -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Informasi Pembayaran</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="mb-2"><span class="font-medium">Metode Pembayaran:</span> {{ ucfirst($order->payment_method) }}</p>
            </div>
        </div>
    </div>
@endsection 