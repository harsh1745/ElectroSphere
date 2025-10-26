<?php

namespace App\Http\Controllers;

// Zaroori Classes aur Models ko Import karo:
use App\Http\Requests\StoreProductRequest; // ✅ Undefined type 'App\Http\Requests\StoreProductRequest' fix
use App\Models\Category;                  // ✅ Undefined type 'App\Models\Category' fix
use App\Models\Product;                   // Products table mein data save karne ke liye
use Illuminate\Routing\Controller;        // Base Controller class
use Illuminate\Http\Request;              // Request class agar StoreProductRequest use nahi ho raha ho
use Illuminate\Support\Facades\Storage; // Storage facade import karein


// Agar tumhara Controller Admin folder mein hai:
// use App\Http\Controllers\Controller; // Agar tum default App\Http\Controllers\Controller use karte ho

class ProductController extends Controller
{
    public function index()
    {
        // Sabhi products ko fetch karo
        $products = Product::all();

        // Ek naya view file banao: resources/views/admin/products/index.blade.php
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        // 'Category' is ab defined
        $categories = Category::all();
        return view('admin.products.addproduct', compact('categories'));
    }

    // public function store(StoreProductRequest $request)
    // {
    //     // --- 1. Undefined variable '$imagePath' fix: ---
    //     // $imagePath ko pehle hi initialize (define) karna zaroori hai.
    //     $imagePath = null;

    //     // Image Upload Logic
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('public/products');
    //         $imagePath = str_replace('public/', '', $imagePath);
    //     }

    //     // Product::create mein 'Product' ab defined hai
    //     Product::create([
    //         'name' => $request->name,
    //         'category_id' => $request->category_id,
    //         'price' => $request->price,
    //         'description' => $request->description,
    //         'image' => $imagePath, // Ab $imagePath defined hai, chahe 'if' block chala ho ya nahi.
    //     ]);

    //     return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    // }

    public function store(StoreProductRequest $request)
    {
        $imagePath = null;

        // ✅ Image Upload Logic
        if ($request->hasFile('image')) {
            // File ko "storage/app/public/products" me store karega
            // aur database me sirf "products/filename.jpg" save karega
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath, // e.g. "products/shoe1.jpg"
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product added successfully!');
    }

    public function edit(Product $product)
    {
        // Categories ko fetch karna zaroori hai dropdown ke liye
        $categories = Category::all();

        // resources/views/admin/products/edit.blade.php view ko load karo
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Product ko database mein update karne ke liye
     * @param \App\Http\Requests\StoreProductRequest $request (Validation ke liye StoreProductRequest use kar sakte hain)
     * @param \App\Models\Product $product (Product jise update karna hai)
     */
    // public function update(StoreProductRequest $request, Product $product)
    // {
    //     // 1. Image Handling (Agar naya image upload hua hai)
    //     $imagePath = $product->image; // Existing image path ko pehle le lo

    //     if ($request->hasFile('image')) {
    //         // Optional: Purana image delete kar sakte ho yahaan
    //         // Storage::delete($product->image);

    //         // Naya image store karo
    //         $imagePath = $request->file('image')->store('public/products');
    //         $imagePath = str_replace('public/', '', $imagePath);
    //     }

    //     // 2. Product ko update karo
    //     $product->update([
    //         'name' => $request->name,
    //         'category_id' => $request->category_id,
    //         'price' => $request->price,
    //         'description' => $request->description,
    //         'image' => $imagePath,
    //     ]);

    //     // 3. Product List page par redirect karo
    //     return redirect()->route('admin.products.index')
    //         ->with('success', 'Product updated successfully!');
    // }


    public function update(StoreProductRequest $request, Product $product)
    {
        $imagePath = $product->image; // Existing image path ko pehle le lo

        if ($request->hasFile('image')) {
            // Naya image store karo
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }
}
