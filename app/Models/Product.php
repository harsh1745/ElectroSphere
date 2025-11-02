<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Base model
use App\Models\Category; // <-- 💥 FIX: Yeh line zaroori hai aur iska path sahi hona chahiye

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug', // Agar tum slug use karte ho
        'category_id', 
        'price',
        'description',
        'image',
    ];

    // Category Relationship (Jo ShopController mein use ho raha hai)
    public function category()
    {
        // Category model ko use karne ke liye, upar 'use App\Models\Category;' hona chahiye.
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
    // ... baaki methods ...
}
