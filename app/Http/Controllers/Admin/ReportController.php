<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date') 
            ? Carbon::parse($request->input('start_date')) 
            : Carbon::now()->startOfMonth();
            
        $endDate = $request->input('end_date') 
            ? Carbon::parse($request->input('end_date')) 
            : Carbon::now();

        // Statistik Umum
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Penjualan per Kategori
        $salesByCategory = Product::join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->groupBy('products.category')
            ->select(
                'products.category',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity) as total_items'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->get();

        // Produk Terlaris
        $topProducts = Product::withCount(['orderItems as total_sales' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', 'completed');
                });
            }])
            ->withSum(['orderItems as revenue' => function($query) use ($startDate, $endDate) {
                $query->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', 'completed');
                });
            }], DB::raw('quantity * price'))
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        // Tren Penjualan Harian
        $dailySales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('date')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(total_amount) as total_revenue')
            )
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'salesByCategory',
            'topProducts',
            'dailySales'
        ));
    }
}
