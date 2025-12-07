<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order; // Order model ko use karein
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($order_id)
    {
        // Order ID se order fetch karein
        $order = Order::findOrFail($order_id);

        // ✅ Yahan Invoice View return karein
        return view('frontend.invoice.show', compact('order'));
    }
}
