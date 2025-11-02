<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

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
        $products = $productsQuery->paginate(9)->withQueryString();

        // 🧩 Get min & max product price for slider
        $priceRange = [
            'min' => Product::min('price'),
            'max' => Product::max('price')
        ];

        return view('frontend.shop.index', compact(
            'products',
            'categories',
            'currentCategory',
            'minPrice',
            'maxPrice',
            'priceRange'
        ));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('frontend.shop.show', compact('product'));
    }
}
