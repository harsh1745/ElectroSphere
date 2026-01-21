<?php

namespace App\Http\Controllers;

// Zaroori Classes aur Models ko Import karo:
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.addproduct', compact('categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        $validated['image'] = $imagePath;

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('admin_toast', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }


    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'manufacturer' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            $validated['image'] = $product->image;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('admin_toast', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('admin_toast', 'Product deleted successfully!');
    }
    
}
