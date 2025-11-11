<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductSaleController extends Controller
{
    // Show Flash Sale products
    public function flashSales()
    {
        $products = Product::where('is_flash_sale', 1)->take(10)->get();

        $wishlistIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
            : [];

        return view('flash-sales', compact('products', 'wishlistIds'));
    }

    // Show individual product
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product-details', compact('product'));
    }
}
