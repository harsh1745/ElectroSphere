<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // === YEH METHOD ADD KARO! ===
    public function index()
    {
        // Category list page ke liye data fetch karna.
        $categories = Category::all();

        // Yahaan tumhe category list dikhane ke liye ek view file banana padega
        // resources/views/admin/categories/index.blade.php
        return view('admin.categories.index', compact('categories'));
    }
    // ============================

    // Category Add Form dikhane ke liye (Pehle se मौजूद है)
    public function create()
    {
        return view('admin.categories.create');
    }

    // Category database mein save karne ke liye (Pehle se मौजूद है)
    public function store(Request $request)
    {
        // ... validation and insertion logic ...

        // Ab redirect theek se kaam karega!
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category added successfully!');
    }
}
