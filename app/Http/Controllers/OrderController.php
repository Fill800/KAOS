<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
            'payment_method' => 'required|in:transfer,ewallet'
        ]);

        try {
            DB::beginTransaction();

            // Ambil items dari cart dan pastikan ada produk
            $cartItems = Cart::where('user_id', auth()->id())
                ->with('product')
                ->get();
            
            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Keranjang belanja kosong');
            }

            // Validasi stok produk
            foreach ($cartItems as $item) {
                if (!$item->product) {
                    throw new \Exception('Produk tidak ditemukan');
                }
                // Tambahkan validasi stok jika diperlukan
            }

            // Hitung total
            $total = $cartItems->sum(function($item) {
                return $item->product->price * $item->quantity;
            });

            // Update user information if checkbox is checked
            if ($request->has('update_user_info')) {
                auth()->user()->update([
                    'address' => $request->shipping_address,
                    'phone' => $request->phone_number
                ]);
            }

            // Buat order baru
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'phone_number' => $request->phone_number,
                'payment_method' => $request->payment_method
            ]);

            // Buat order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'custom_design' => $item->custom_design ?? null
                ]);
            }

            // Kosongkan cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('user.orders')
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order Creation Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        // Pastikan user hanya bisa melihat ordernya sendiri
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.order-detail', compact('order'));
    }
} 