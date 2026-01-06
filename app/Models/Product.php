<?php

namespace App\Models;

// ✅ ZAROORI IMPORTS
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Automatic slug generation ke liye
use App\Models\Category;    // Category Relationship ke liye
use App\Models\Wishlist;   // Wishlist Relationship ke liye
use Illuminate\Database\Eloquent\Casts\Attribute; // ✅ Accessor ke liye zaroori

class Product extends Model
{
    // ✅ Best Practice: Factories use karne ke liye
    use HasFactory;
    // ✅ NEW: Stock Status Constants
    const STATUS_IN_STOCK = 'in_stock';
    const STATUS_NOT_AVAILABLE = 'not_available';
    const STATUS_LOW_STOCK = 'low_stock';

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'description',
        'image',

        // ✅ STOCK STATUS HATA DIYA, 'stock' input add kiya
        'manufacturer',
        'stock',
    ];

    // public static function getStockStatuses()
    // {
    //     return [
    //         self::STATUS_IN_STOCK => 'In Stock',
    //         self::STATUS_NOT_AVAILABLE => 'Not Available',
    //         self::STATUS_LOW_STOCK => 'Low Stock',
    //     ];
    // }

    protected function stockStatus(): Attribute
    {
        $stock = $this->stock;

        return Attribute::make(
            get: function () use ($stock) {
                if ($stock > 50) {
                    return 'in_stock';
                } elseif ($stock > 0) { // 1 se 500 tak
                    return 'low_stock';
                } else { // stock == 0
                    return 'not_available';
                }
            },
        );
    }


    /**
     * ✅ NEW: Automatic Slug Generation (Har baar save hone se pehle)
     */
    protected static function boot()
    {
        parent::boot();

        // Jab bhi model create ya update ho, slug generate karo
        static::creating(function ($product) {
            // Check if slug is already set manually
            if (empty($product->slug) && !empty($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            // Agar naam change hua hai aur slug manually set nahi kiya gaya hai
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }


    // --- Relationships ---

    // Category Relationship
    public function category()
    {
        // Category model ko use karne ke liye, upar 'use App\Models\Category;' zaroori hai
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // Wishlist Relationship
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Naya: Reviews Relationship (Product Details Page ke liye zaroori)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
