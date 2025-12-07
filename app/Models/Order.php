<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * ✅ FIX: $fillable array ko aapke simple 'orders' table se match karein
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'status', // Aapne confirm kiya ki 'status' column hai

        // ✅ YEH SAARI FIELDS ZAROORI HAIN (Mass Assignment ke liye)
        'address_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'payment_method',
        'payment_status',   // ← NEW
        'shipping_address_json',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Aapke table 'order_details' ke hisaab se relationship
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
