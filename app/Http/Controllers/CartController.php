<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Cek apakah produk sudah ada di cart user
        $existingCart = Cart::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();
        
        if ($existingCart) {
            // Jika sudah ada, update quantity
            $existingCart->increment('quantity');
        } else {
            // Jika belum ada, buat cart baru
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => 1,
                'custom_design' => null // bisa diisi nanti saat fitur custom design
            ]);
        }
        
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }
}
