<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    // ✅ Quantity field ko fillable rakha gaya hai
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity', 
    ];

    /**
     * Define relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define relationship with the Product model.
     */
    public function product()
    {
        // Cart item product details ke liye
        return $this->belongsTo(Product::class);
    }
}