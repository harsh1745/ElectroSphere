<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Database schema: category_id, name, description, price, image
     */
    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'image',
        // 'created_at' and 'updated_at' are handled automatically by Laravel
    ];

    // Agar tum 'category_id' ko Foreign Key relation se link karna chaho toh:
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
}
