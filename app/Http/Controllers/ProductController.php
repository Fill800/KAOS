<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //

    public function customize(Product $product)
    {
        return view('user.product.customize', compact('product'));
    }
}
