<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    public static function getCartCount()
    {
        if (!Auth::check()) return 0;
        return Cart::where('user_id', Auth::id())->sum('quantity');
    }


    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        $cartSubtotal = $cartItems->sum(function ($item) {
            return $item->product ? ($item->product->price * $item->quantity) : 0;
        });

        return view('frontend.cart.index', compact('cartItems', 'cartSubtotal'));
    }

    public function add(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Login required.'], 401);
        }

        $userId = Auth::id();
        $quantity = $request->input('quantity', 1);

        $cartItem = Cart::where('user_id', $userId)->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
            $action = 'updated';
        } else {
            Cart::create(['user_id' => $userId, 'product_id' => $product->id, 'quantity' => $quantity]);
            $action = 'added';
        }

        return response()->json([
            'status' => 'success',
            'success' => true,
            'action' => $action,
            'count' => self::getCartCount(),
        ]);
    }

public function update(Request $request)
{
    if ($request->has('cart_id') && $request->has('quantity')) {

        $cartItem = Cart::where('id', $request->cart_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($cartItem) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }

        return response()->json(['status' => 'updated']);
    }

    $validated = $request->validate([
        'quantities' => 'required|array',
        'quantities.*' => 'required|integer|min:1',
        'cart_ids' => 'required|array',
        'cart_ids.*' => 'required|exists:carts,id',
    ]);

    foreach ($validated['cart_ids'] as $i => $cartId) {
        $cartItem = Cart::where('id', $cartId)
            ->where('user_id', Auth::id())->first();

        if ($cartItem) {
            $cartItem->quantity = $validated['quantities'][$i];
            $cartItem->save();
        }
    }

    return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
}

    public function remove(Cart $cartItem)
    {
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $cartItem->delete();

        return response()->json([
            'status' => 'removed',
            'count' => self::getCartCount(),
            'message' => 'Item removed from cart.'
        ]);
    }
}
