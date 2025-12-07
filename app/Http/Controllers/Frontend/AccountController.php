<?php

namespace App\Http\Controllers\Frontend; // Assuming this is your namespace

use App\Http\Controllers\Controller; // ✅ ADD THIS LINE
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request; // Added request in case you use it later

class AccountController extends Controller
{
    // ... (other methods) ...
    
    /**
     * Shows a list of all past orders/invoices for the logged-in user.
     */
    public function invoicesIndex()
    {
        $userId = Auth::id();
        
        // Fetch all orders/invoices, ordered by most recent
        $invoices = Order::where('user_id', $userId)
                         ->latest()
                         ->get(); // You can use paginate() here if there are many orders

        return view('frontend.account.invoices.index', compact('invoices'));
    }
    
    /**
     * Shows a specific invoice page.
     */
    public function showInvoice($order_id)
    {
        $order = Order::where('id', $order_id)
                       ->where('user_id', Auth::id())
                       ->with('items.product') // Load related items and products
                       ->firstOrFail();

        // The existing invoice/show.blade.php can be reused
        return view('frontend.invoice.show', compact('order'));
    }
}