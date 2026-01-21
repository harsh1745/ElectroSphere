<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     * * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'street_address', 
        'apartment_suite',
        'city',
        'state',
        'zip_code',
        'country',
        'is_default',
        'type',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}