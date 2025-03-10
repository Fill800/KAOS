@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('header', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    <!-- Filter Date -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.reports') }}" method="GET" class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Laporan Penjualan</h2>
                        <p class="text-sm text-gray-500">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" id="end_date"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="self-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Pesanan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Rata-rata Pesanan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Rata-rata Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-bar text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales by Category -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-tags text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Penjualan per Kategori</h3>
                    <p class="text-sm text-gray-500">Rincian penjualan berdasarkan kategori produk</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Pesanan
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Item
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Pendapatan
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($salesByCategory as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($category->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($category->total_orders) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($category->total_items) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($category->total_revenue, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Products -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-crown text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Produk Terlaris</h3>
                <p class="text-sm text-gray-500">5 produk dengan penjualan tertinggi</p>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($topProducts as $product)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 w-12 h-12">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="w-12 h-12 object-cover rounded-lg"
                                 alt="{{ $product->name }}">
                        @else
                            <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                        <p class="text-sm text-gray-500">{{ $product->total_sales }} terjual</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">
                        Rp {{ number_format($product->revenue, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Daily Sales Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center space-x-4 mb-6">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-chart-area text-blue-600 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-medium text-gray-900">Tren Penjualan Harian</h3>
                <p class="text-sm text-gray-500">Grafik penjualan per hari</p>
            </div>
        </div>

        <div class="h-64">
            <canvas id="dailySalesChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dailySalesChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailySales->pluck('date')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d M');
            })) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($dailySales->pluck('total_revenue')) !!},
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 