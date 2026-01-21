<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';


    protected $fillable = [
        'user_id',
        'total_amount',
        'status',

        'address_id',
        'order_number',
        'subtotal',
        'shipping_cost',
        'payment_method',
        'payment_status',
        'shipping_address_json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
