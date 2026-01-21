<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; 
use App\Models\Review; 

class ReviewController extends Controller
{

    public function store(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Login required to submit a review.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $userId = Auth::id();

        $existingReview = Review::where('user_id', $userId)
                                ->where('product_id', $product->id)
                                ->exists();

        if ($existingReview) {
            return back()->with('error', 'You have already submitted a review for this product.');
        }

        Review::create([
            'user_id' => $userId,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Your review has been submitted and is awaiting approval!');
    }
}