<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Str;
// ✅ Yeh do (2) imports zaroori hain Wishlist check ke liye
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::all();
    $productsQuery = Product::query();

    // 🔒 SAME RANDOM ORDER FOR PAGINATION
    $seed = session()->get('shop_random_seed');
    if (!$seed) {
        $seed = rand(1, 100000);
        session()->put('shop_random_seed', $seed);
    }

    // Get filters
    $currentCategory = $request->input('category');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');

    // 🧩 Category filter
    if (!empty($currentCategory) && $currentCategory !== 'all') {
        $productsQuery->where('category_id', $currentCategory);
    }

    // 🧩 Price Range filter
    if (!empty($minPrice)) {
        $productsQuery->where('price', '>=', $minPrice);
    }

    if (!empty($maxPrice)) {
        $productsQuery->where('price', '<=', $maxPrice);
    }

    // 🧩 ONLY AVAILABLE PRODUCTS
    $productsQuery->where('stock', '>', 0);

    // 🎲 RANDOM ORDER (STABLE)
    $products = $productsQuery
        ->orderByRaw("RAND($seed)")
        ->paginate(12)
        ->withQueryString();

    // 🧩 Min & Max price for filter UI
    $priceRange = [
        'min' => Product::min('price'),
        'max' => Product::max('price'),
    ];

    $wishlistProductIds = [];
    if (Auth::check()) {
        $wishlistProductIds = Wishlist::where('user_id', Auth::id())
            ->pluck('product_id')
            ->toArray();
    }

    return view('frontend.shop.index', compact(
        'products',
        'categories',
        'currentCategory',
        'minPrice',
        'maxPrice',
        'priceRange',
        'wishlistProductIds'
    ));
}


    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $reviews = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $isWishlisted = false;
        if (Auth::check()) {
            $isWishlisted = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        // ⭐ REAL SOLD COUNT — FROM order_details TABLE
        $soldCount = \App\Models\OrderDetail::where('product_id', $product->id)
            ->sum('quantity');

        // ⭐ Rating — Calculate average rating from reviews
        $rating = $reviews->avg('rating') ?? 0;

        // Manufacturer
        $manufacturerDetails = $product->manufacturer ?? "Manufacturer info not available.";

        return view('frontend.shop.show', compact(
            'product',
            'reviews',
            'isWishlisted',
            'rating',
            'soldCount',
            'manufacturerDetails'
        ));
    }
}
