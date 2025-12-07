<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Checkout page (CAPTCHA logic ke saath)
     */
    public function index()
    {
        $userId = Auth::id();
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(function ($item) {
            return $item->product ? ($item->product->price * $item->quantity) : 0;
        });

        $addresses = Address::where('user_id', $userId)->get();

        // --- CAPTCHA LOGIC ---
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        session(['captcha_answer' => $num1 + $num2]);
        $captcha_question = "What is $num1 + $num2 ?";
        // --- END CAPTCHA ---

        return view('frontend.checkout.index', compact(
            'cartItems',
            'subtotal',
            'addresses',
            'captcha_question'
        ));
    }

    /**
     * Order Process (Sirf COD ke liye)
     */
    public function process(Request $request)
    {
        // --- 1. VALIDATION (Sirf Address aur CAPTCHA) ---
        $request->validate([
            'captcha_answer' => 'required|numeric',
            'selected_address' => 'required|integer|exists:addresses,id',
            'payment_method' => 'required|in:cod', // Ensure hidden field 'cod' aa raha hai
        ]);


        // CAPTCHA Check
        $correct_answer = session('captcha_answer');
        if ($request->captcha_answer != $correct_answer) {
            return redirect()->back()->withErrors(['captcha_answer' => 'Security check failed. Please try again.']);
        }
        session()->forget('captcha_answer');
        // --- END CAPTCHA VALIDATION ---


        // --- 2. GET DATA ---
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();
        $address = Address::find($request->selected_address);

        if ($cartItems->isEmpty()) {
            return redirect()->route('shop.index')->with('error', 'cart is empty.');
        }

        // --- 3. CALCULATE TOTAL ---
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $shippingCost = $subtotal >= 5000 ? 0.00 : 177.00;
        $totalAmount = $subtotal + $shippingCost;
        // Address fetch kiya
        $address = Address::find($request->selected_address);


        $order = Order::create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'order_number' => 'ORD-' . Str::random(10),
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total_amount' => $totalAmount,
            'payment_method' => 'cod',
            'payment_status' => 'pending', // ALWAYS COD → pending
            'order_status' => 'new',
            'shipping_address_json' => json_encode([
                'name' => $address->full_name,
                'phone' => $address->phone,
                'street' => $address->street_address,
                'city' => $address->city,
                'state' => $address->state,
                'zip_code' => $address->zip_code,
            ]),
        ]);


        //         // --- 4. CREATE ORDER (COD Logic Only) ---
        //         $shippingAddressJson = json_encode([
        //     'name' => $address->full_name,
        //     'phone' => $address->phone,
        //     'street' => $address->street_address,
        //     'city' => $address->city,
        //     'state' => $address->state,
        //     'zip_code' => $address->zip_code,
        // ]);

        // $order = Order::create([
        //     'user_id' => $user->id,
        //     'address_id' => $address->id,
        //     'order_number' => 'ORD-' . Str::random(10),
        //     'subtotal' => $subtotal,
        //     'shipping_cost' => $shippingCost,
        //     'total_amount' => $totalAmount,

        //     // ✅ FIX 1: Sirf 'status' column use karein aur uski value 'pending' set karein
        //     'payment_method' => 'cod', 
        //     'status' => 'pending', // Payment status aur Order status dono ko 'pending' rakha

        //     // ❌ Hata diya: 'payment_status' => 'pending', 
        //     // ❌ Hata diya: 'order_status' => 'new', 

        //     'shipping_address_json' => $shippingAddressJson,
        // ]);

        // --- 5. SAVE ORDER ITEMS ---
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
            // ✅ FIX: DEDUCT STOCK FROM INVENTORY
            if ($item->product) {
                $product = $item->product;

                // Stock ko ghatayein
                $newStock = $product->stock - $item->quantity;

                // Ensure stock negative na ho jaaye (though quantity checks should prevent this)
                $product->stock = max(0, $newStock);

                // Database mein save karein
                $product->save();
            }
            // Optional: Yahan stock update karein
        }

        // --- 6. CLEAR CART ---
        Cart::where('user_id', $user->id)->delete();

        // return redirect()->route('invoice.show', ['order_id' => $order->id])
        //     ->with('success', 'Order placed successfully!');
        return redirect()->route('invoice.show', ['order_id' => $order->id])
            // ✅ Use a specific session key for SweetAlert trigger
            ->with('order_success_message', 'Order ' . $order->order_number . ' placed successfully!');
    }
}
