<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * ✅ FIX: Model ko batayein ki table ka naam 'order_details' hai
     */
    protected $table = 'order_details';

    /**
     * Yeh fields aapke 'order_details' table se match hote hain
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relationships (optional but recommended)
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
