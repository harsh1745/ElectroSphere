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
    // ✅ 1. Get Cart Count for Navbar
    public static function getCartCount()
    {
        if (!Auth::check()) return 0;
        // Total number of items (sum of quantity)
        return Cart::where('user_id', Auth::id())->sum('quantity');
    }


    // 🛒 2. Full Cart Page (index)
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Cart items ko product details ke saath load karo
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        $cartSubtotal = $cartItems->sum(function ($item) {
            // Price ko quantity se multiply karke total subtotal nikalo
            return $item->product ? ($item->product->price * $item->quantity) : 0;
        });

        // Yeh 'frontend.cart.index' view ko load karega
        return view('frontend.cart.index', compact('cartItems', 'cartSubtotal'));
    }

    // ➕ 3. Add to Cart (AJAX from Shop/Product Page)
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
            'count' => self::getCartCount(), // ✅ Navbar Count

        ]);
    }

public function update(Request $request)
{
    // 🌟 CASE 1: AJAX single item update
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

    // 🌟 CASE 2: Normal form submission (fallback)
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

    // ➖ 5. Remove Item (From Cart Page or AJAX)
    public function remove(Cart $cartItem)
    {
        // Policy: Ensure the item belongs to the authenticated user
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $cartItem->delete();

        return response()->json([
            'status' => 'removed',
            'count' => self::getCartCount(), // ✅ Navbar Count
            'message' => 'Item removed from cart.'
        ]);
    }
}
