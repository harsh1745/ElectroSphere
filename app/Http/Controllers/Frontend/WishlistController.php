<?php
// ✅ NOTE: <?php ke baad seedhi agli line mein namespace hona chahiye.

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->has('product')
            ->with('product')
            ->latest()
            ->get();

        return view('frontend.wishlist.index', compact('wishlistItems'));
    }
    public function getDrawerContent()
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->has('product')
            ->with('product')
            ->latest()
            ->get();

        $subtotal = $wishlistItems->sum(function ($item) {
            return $item->product ? $item->product->price : 0;
        });

    return view('frontend.wishlist._drawer_content', compact('wishlistItems', 'subtotal'))->render();
    }

    public function toggle(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $status = 'removed';
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);
            $status = 'added';
        }

        $count = self::getWishlistCount();

        return response()->json([
            'status' => $status,
            'count' => $count
        ]);
    }

    public static function getWishlistCount()
    {
        if (!Auth::check()) return 0;
        return Wishlist::where('user_id', Auth::id())->count();
    }
}
