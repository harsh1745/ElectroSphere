<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Table name agar 'addresses' se alag hai toh yahan define karein
    // protected $table = 'user_addresses'; 

    /**
     * The attributes that are mass assignable.
     * Checkout form se data save karte waqt yeh fields fillable hone chahiye.
     * Aapko is list ko apne migration ke fields ke hisaab se update karna hoga.
     * * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name', // Jaise 'Antony Hopkins'
        'phone',
        'street_address', // Line 1: 2392 Main Avenue
        'apartment_suite', // Line 2: Suite #242 (optional)
        'city',
        'state',
        'zip_code',
        'country',
        'is_default', // 1 ya 0
        'type', // 'shipping' ya 'billing'
    ];

    /**
     * User ke saath relationship define karna.
     * Ek address sirf ek user ko belong karta hai.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}