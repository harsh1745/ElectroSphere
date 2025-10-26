<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    
    // Table ka naam order_details hai
    protected $table = 'order_details';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price', // Jis price par product khareeda gaya tha [cite: 24]
    ];

    /**
     * OrderDetails belong to an Order (order_details.order_id -> orders.id).
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * OrderDetails belong to a Product (order_details.product_id -> products.id).
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}