<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->take(5)->get();
        return view('user.dashboard', compact('orders'));
    }

    public function catalog()
    {
        $products = Product::latest()->paginate(12);
        return view('user.catalog', compact('products'));
    }

    public function cart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        return view('user.cart', compact('cartItems', 'total'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('user.orders', compact('orders'));
    }

    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart')->with('error', 'Keranjang belanja kosong');
        }
        
        return view('user.checkout', compact('cartItems', 'total'));
    }
} 