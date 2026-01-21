<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show($order_id)
    {
        $order = Order::findOrFail($order_id);

        return view('frontend.invoice.show', compact('order'));
    }
}
