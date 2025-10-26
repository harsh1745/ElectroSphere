<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Database schema: user_id, total_amount, status
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        // 'created_at' and 'updated_at' are handled automatically
    ];

    /**
     * The table associated with the model.
     * Default name 'orders' hai, jo sahi hai.
     * @var string
     */
    protected $table = 'orders';

    /**
     * Casts the 'status' enum to string (optional but good practice)
     */
    protected $casts = [
        'total_amount' => 'decimal:2', // total_amount decimal(10,2) hai
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Ek order ek user se belong karta hai (orders.user_id -> users.id).
     */
    public function user()
    {
        // Assuming your customer model is App\Models\User
        return $this->belongsTo(User::class);
    }

    /**
     * Ek order mein bahut saare products ho sakte hain (order_details table ke through).
     */
    public function details()
    {
        // OrderDetails model (jo tum order_details table se banaoge) se link karta hai.
        return $this->hasMany(OrderDetail::class); 
    }
}