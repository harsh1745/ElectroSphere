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

        // Get filters
        $currentCategory = $request->input('category'); // category id
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        // 🧩 Category filter
        if (!empty($currentCategory) && $currentCategory !== 'all') {
            $productsQuery->whereHas('category', function ($query) use ($currentCategory) {
                $query->where('id', $currentCategory);
            });
        }

        // 🧩 Price Range filter
        if (!empty($minPrice)) {
            $productsQuery->where('price', '>=', $minPrice);
        }

        if (!empty($maxPrice)) {
            $productsQuery->where('price', '<=', $maxPrice);
        }

        // 🧩 Get products (paginate)
        $products = $productsQuery->paginate(12)->withQueryString();

        // 🧩 Get min & max product price for slider
        $priceRange = [
            'min' => Product::min('price'),
            'max' => Product::max('price')
        ];

        // 💖 FIX: Wishlist Product IDs ko fetch karna
        $wishlistProductIds = [];
        if (Auth::check()) {
            // Agar user logged in hai, toh uske saare wishlisted product_id database se nikaal lo.
            $wishlistProductIds = Wishlist::where('user_id', Auth::id())
                ->pluck('product_id') // Sirf product IDs chahiye
                ->toArray(); // Blade mein use karne ke liye array bana lo
        }

        return view('frontend.shop.index', compact(
            'products',
            'categories',
            'currentCategory',
            'minPrice',
            'maxPrice',
            'priceRange',
            // ✅ Ab 'wishlistProductIds' view ko mil jayega
            'wishlistProductIds'
        ));
    }

    public function show($slug)
    {
        // Product ko uske slug se fetch karo
        $product = Product::where('slug', $slug)->firstOrFail();

        // Product ke reviews fetch karo
        $reviews = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        // Wishlist status check
        $isWishlisted = false;
        if (Auth::check()) {
            $isWishlisted = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        // Static dummy data for demonstration (Rating, Sold count, Manufacturer details)
        $rating = 4.5;
        $soldCount = 500;
        $manufacturerDetails = "Manufacturer: XYZ Corp. | Model: RN2019 | Warranty: 1 Year.";

        return view('frontend.shop.show', compact('product', 'reviews', 'isWishlisted', 'rating', 'soldCount', 'manufacturerDetails'));
    }
}
