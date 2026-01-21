<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Support\Str;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(Request $request)
{
    $categories = Category::all();
    $productsQuery = Product::query();

    $seed = session()->get('shop_random_seed');
    if (!$seed) {
        $seed = rand(1, 100000);
        session()->put('shop_random_seed', $seed);
    }

    $currentCategory = $request->input('category');
    $minPrice = $request->input('min_price');
    $maxPrice = $request->input('max_price');

    if (!empty($currentCategory) && $currentCategory !== 'all') {
        $productsQuery->where('category_id', $currentCategory);
    }

    if (!empty($minPrice)) {
        $productsQuery->where('price', '>=', $minPrice);
    }

    if (!empty($maxPrice)) {
        $productsQuery->where('price', '<=', $maxPrice);
    }

    $productsQuery->where('stock', '>', 0);

    $products = $productsQuery
        ->orderByRaw("RAND($seed)")
        ->paginate(12)
        ->withQueryString();

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

        $soldCount = \App\Models\OrderDetail::where('product_id', $product->id)
            ->sum('quantity');

        $rating = $reviews->avg('rating') ?? 0;

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
