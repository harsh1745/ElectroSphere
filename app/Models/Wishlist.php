<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Table ka naam 'wishlists'
    protected $table = 'wishlists';

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'product_id',
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
        return $this->belongsTo(Product::class);
    }
}
