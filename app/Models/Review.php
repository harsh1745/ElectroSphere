<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'status', // e.g., 'approved', 'pending'
    ];

    /**
     * Define relationship with the User model.
     */
    public function user()
    {
        // Har review kisi ek user se belong karta hai
        return $this->belongsTo(User::class);
    }

    /**
     * Define relationship with the Product model.
     */
    public function product()
    {
        // Har review kisi ek product se belong karta hai
        return $this->belongsTo(Product::class);
    }
}