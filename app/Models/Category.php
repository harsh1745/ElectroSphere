<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Agar tumhari table ka naam 'categories' hai, toh yeh line optional hai.
    // protected $table = 'categories'; 

    // Agar tum mass assignment use kar rahe ho (jaise ki Admin panel mein), 
    // toh 'fillable' ya 'guarded' zaroori hai.
    protected $fillable = ['name', 'description'];
}
