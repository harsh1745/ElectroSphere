<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;
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

        'manufacturer',
        'stock',
    ];

    protected function stockStatus(): Attribute
    {
        $stock = $this->stock;

        return Attribute::make(
            get: function () use ($stock) {
                if ($stock > 50) {
                    return 'in_stock';
                } elseif ($stock > 0) {
                    return 'low_stock';
                } else { // stock == 0
                    return 'not_available';
                }
            },
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug) && !empty($product->name)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
