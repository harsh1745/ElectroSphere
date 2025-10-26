<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; // ⬅️ FIX: Base Controller import
use Illuminate\Http\Request;         // ⬅️ FIX: Request class import
use App\Models\Product;              // ⬅️ FIX: Product Model import
use App\Models\Category;             // ⬅️ FIX: Category Model import

class ShopController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all categories to display in the sidebar/filter
        $categories = Category::all();

        // Start with all products
        $products = Product::query();

        // Apply category filter if selected
        if ($request->has('category') && $request->category != '') {
            // Note: Make sure 'category' relationship exists on Product model
            $products->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        // Apply other filters (search example)
        if ($request->has('search') && $request->search != '') {
            $products->where('name', 'like', '%' . $request->search . '%');
        }

        // Paginate the results
        $products = $products->paginate(9);

        return view('frontend.shop.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        // Product Model use ho raha hai
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('frontend.shop.show', compact('product'));
    }
}
