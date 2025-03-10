<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total pesanan dan pertumbuhan
        $totalOrders = Order::count();
        $lastMonthOrders = Order::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $orderGrowth = $lastMonthOrders > 0 
            ? round(($totalOrders - $lastMonthOrders) / $lastMonthOrders * 100, 1)
            : 100;

        // Menghitung total pendapatan dan pertumbuhan
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $lastMonthRevenue = Order::where('status', 'completed')
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->sum('total_amount');
        $revenueGrowth = $lastMonthRevenue > 0 
            ? round(($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue * 100, 1)
            : 100;

        // Menghitung produk dan stok
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<', 10)->count();

        // Menghitung pengguna
        $totalUsers = User::where('is_admin', false)->count();
        $newUsers = User::where('is_admin', false)
            ->whereMonth('created_at', '=', Carbon::now()->month)
            ->count();

        // Mengambil pesanan terbaru
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Mengambil produk terpopuler
        $popularProducts = Product::withCount(['orderItems as total_sales' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'completed');
                });
            }])
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'orderGrowth',
            'totalRevenue',
            'revenueGrowth',
            'totalProducts',
            'lowStockProducts',
            'totalUsers',
            'newUsers',
            'recentOrders',
            'popularProducts'
        ));
    }
} 