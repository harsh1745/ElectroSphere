<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Agar aap 'admins' naam ki alag table use kar rahe hain,
 * toh is model ko use karein.
 * Agar aap sirf 'users' table use kar rahe hain, toh is model ki zarurat nahi hai.
 * Hum yahaan assume kar rahe hain ki aapne 'admins' table banayi hai.
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    // Yahaan 'admin' guard use hoga
    protected $guard = 'admin';

    // Database table ka naam
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
