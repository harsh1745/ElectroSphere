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


    public function store(Request $request) // ✅ Request use kiya, agar StoreProductRequest nahi hai
    {
        // Validation rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'manufacturer' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0', // ✅ Validation mein stock hai
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }
        $validated['image'] = $imagePath;

        // Product::create() mein validated data pass kiya gaya hai
        Product::create($validated);

        // ✅ FIX: Default 'success' alert message ko hatakar 'admin_toast' use kiya
        return redirect()->route('admin.products.index')->with('admin_toast', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }


    public function update(Request $request, Product $product) // ✅ Request use kiya
    {
        // Validation rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            // Image is nullable, validation is below
            'manufacturer' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0', // ✅ Validation mein stock hai
            'image' => 'nullable|image|max:2048',
        ]);

        // 2. Image Handling
        if ($request->hasFile('image')) {
            // Optional: Purana image delete karo
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            // Agar image field validation mein hai aur file nahi aayi, toh existing image rakho
            $validated['image'] = $product->image;
        }

        // 3. Database mein data save karo
        $product->update($validated);

        // ✅ FIX: Default 'success' alert message ko hatakar 'admin_toast' use kiya
        return redirect()->route('admin.products.index')->with('admin_toast', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Optional: Image delete karo
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        // ✅ FIX: Default 'success' alert message ko hatakar 'admin_toast' use kiya
        return redirect()->route('admin.products.index')->with('admin_toast', 'Product deleted successfully!');
    }
}
