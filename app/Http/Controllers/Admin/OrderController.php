<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; // Order Model ko import karo

class OrderController extends Controller
{
    /**
     * Order list dikhane ke liye
     */
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Single order details dikhane ke liye
     */
    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Order status update karne ke liye (e.g., pending to completed)
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,completed,cancelled']);
        
        $order->update(['status' => $request->status]);
        
        return back()->with('success', 'Order status updated successfully.');
    }
}