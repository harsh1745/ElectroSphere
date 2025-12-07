{{-- resources/views/admin/orders/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Manage All Orders')

@section('content')

<div class="container-fluid" style="margin-top: 2rem;">
    <x-admin.back />
    <h1 class="h3 mb-4 text-gray-800">Order Management</h1>

    @if($orders->isEmpty())
    <div class="alert alert-info">No orders have been placed yet.</div>
    @else
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number ?? $order->id }}</td>
                            <td>{{ $order->user->name ?? 'N/A' }}</td>
                            <td>${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                @php
                                $badge = match($order->status) {
                                'completed' => 'success',
                                'shipped' => 'info',
                                'processing' => 'primary',
                                'pending' => 'warning',
                                'cancelled' => 'danger',
                                'refunded' => 'dark',
                                default => 'secondary',
                                };

                                @endphp
                                <span class="badge bg-{{ $badge }} text-uppercase">{{ $order->status }}</span>
                            </td>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>
                                {{-- View/Manage Button --}}
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                    Manage
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $orders->links() }}
        </div>
    </div>
    @endif
</div>

@endsection