<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        $shippingAddress = json_decode($order->shipping_address_json ?? '[]', true);

        return view('admin.orders.show', compact('order', 'shippingAddress'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled,refunded'
        ]);

        $newStatus = $request->status;

        // --- CASE 1: COMPLETED → DEDUCT STOCK + PAYMENT PAID
        if ($newStatus === 'completed' && $order->status !== 'completed') {

            DB::beginTransaction();

            try {

                foreach ($order->items as $item) {
                    $product = $item->product;

                    if ($product && $product->stock >= $item->quantity) {
                        $product->decrement('stock', $item->quantity);
                    } else {
                        DB::rollBack();
                        return back()->with('error', "Insufficient stock for {$product->name}");
                    }
                }

                $order->update([
                    'status' => 'completed',
                    'payment_status' => 'paid'
                ]);

                DB::commit();
                return back()->with('success', 'Order completed & stock updated successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error: ' . $e->getMessage());
            }
        }

        // --- CASE 2: CANCELLED
        if ($newStatus === 'cancelled') {
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);

            return back()->with('success', 'Order cancelled!');
        }

        // --- CASE 3: REFUNDED
        if ($newStatus === 'refunded') {
            $order->update([
                'status' => 'refunded',
                'payment_status' => 'refunded'
            ]);

            return back()->with('success', 'Order refunded!');
        }


        // --- CASE 4: Pending / Processing / Shipped
        $order->update([
            'status' => $newStatus,
            'payment_status' => $order->payment_status ?? 'pending'
        ]);

        return back()->with('success', "Order updated to {$newStatus}.");
    }
}
