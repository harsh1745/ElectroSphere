<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; 
use App\Models\Review; // Review Model import karein

class ReviewController extends Controller
{
    /**
     * Review Form submission ko handle karta hai.
     */
    public function store(Request $request, Product $product)
    {
        // 1. Authentication Check
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Login required to submit a review.');
        }

        // 2. Validation
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $userId = Auth::id();

        // 3. Prevent Duplicate Review
        $existingReview = Review::where('user_id', $userId)
                                ->where('product_id', $product->id)
                                ->exists();

        if ($existingReview) {
            return back()->with('error', 'You have already submitted a review for this product.');
        }

        // 4. Store Review
        Review::create([
            'user_id' => $userId,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending', // Default to pending for admin moderation
        ]);

        return back()->with('status', 'Your review has been submitted and is awaiting approval!');
    }
}