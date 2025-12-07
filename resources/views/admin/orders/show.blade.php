{{-- resources/views/admin/orders/show.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)

@section('content')

{{-- 🔥 SAFE FLASH DATA (NO VS CODE ERRORS) --}}
<div id="order-flash"
    data-success="{{ session('success') }}"
    data-error="{{ session('error') }}">
</div>

<div class="container-fluid">
    <x-admin.back />

    <h1 class="h3 mb-4 text-gray-800">Order #{{ $order->order_number ?? $order->id }}</h1>

    <div class="row">

        {{-- LEFT COLUMN --}}
        <div class="col-lg-8">
            <div class="card shadow mb-4 order-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Status & Actions</h6>
                </div>
                <div class="card-body">

                    @php
                    $badgeClass = match($order->status) {
                    'pending' => 'badge-pending',
                    'processing' => 'badge-processing',
                    'shipped' => 'badge-shipped',
                    'completed' => 'badge-completed',
                    'cancelled' => 'badge-cancelled',
                    'refunded' => 'badge-refunded',
                    default => 'badge-pending'
                    };

                    @endphp

                    {{-- Status --}}
                    <h5 class="mb-3">
                        Current Status:
                        <span class="status-badge {{ $badgeClass }}">
                            {{ strtoupper($order->status) }}
                        </span>
                    </h5>

                    {{-- Status Update Form --}}
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}"
                        method="POST"
                        class="d-flex gap-2 align-items-end">
                        @csrf
                        @method('PUT')

                        <div class="flex-grow-1">
                            <label class="form-label">Change Status:</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Pending</option>
                                <option value="processing" {{ $order->status=='processing'?'selected':'' }}>Processing</option>
                                <option value="shipped" {{ $order->status=='shipped'?'selected':'' }}>Shipped</option>
                                <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Completed</option>
                                <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Cancelled</option>
                                <option value="refunded" {{ $order->status=='refunded'?'selected':'' }}>Refunded</option>
                            </select>

                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                    <hr>

                    {{-- Shipping Address --}}
                    <h6 class="mt-4 font-weight-bold">Shipping Address</h6>

                    @if($shippingAddress)
                    <address>
                        {{ $shippingAddress['name'] }} <br>
                        {{ $shippingAddress['street'] }} <br>
                        {{ $shippingAddress['city'] }},
                        {{ $shippingAddress['state'] }} -
                        {{ $shippingAddress['zip_code'] }}<br>
                        Phone: {{ $shippingAddress['phone'] }}
                    </address>
                    @else
                    <p class="text-danger">Shipping address data missing.</p>
                    @endif

                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4 order-card">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Financial Summary</h6>
                </div>
                <div class="card-body">

                    <p>Customer: {{ $order->user->name ?? 'Guest' }}</p>
                    <p>Order Date: {{ $order->created_at->format('Y-m-d') }}</p>
                    <p>Payment Method: <b>{{ strtoupper($order->payment_method) }}</b></p>

                    <div class="summary-box p-3 mt-3 rounded shadow-sm">

                        <div class="summary-row d-flex justify-content-between mb-2">
                            <span class="text-muted fw-semibold">Subtotal:</span>
                            <span class="fw-bold">${{ number_format($order->subtotal, 2) }}</span>
                        </div>

                        <div class="summary-row d-flex justify-content-between mb-2">
                            <span class="text-muted fw-semibold">Shipping:</span>
                            <span class="fw-bold">${{ number_format($order->shipping_cost, 2) }}</span>
                        </div>

                        <hr>

                        <div class="summary-row d-flex justify-content-between mt-2">
                            <span class="fw-bold fs-5">Grand Total:</span>
                            <span class="fw-bold fs-5 text-danger">
                                ${{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- ORDER ITEMS TABLE --}}
    <div class="card shadow mb-4 order-card">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Items Ordered</h6>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                        <th>Stock Now</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Deleted Product' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price,2) }}</td>
                        <td>${{ number_format($item->price * $item->quantity,2) }}</td>
                        <td>{{ $item->product->stock ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

{{-- CSS --}}
@push('styles')
<style>
    .summary-box {
        background: #f8f9fa;
        border-radius: 8px;
    }

    .summary-row span {
        font-size: 15px;
    }

    .summary-box:hover {
        background: #f8f9fa !important;
        /* No hover change */
    }

    .summary-row:hover {
        background: transparent !important;
        /* No hover effect */
        cursor: default;
    }

    .order-card:hover {
        transform: translateY(-3px);
        transition: 0.25s ease-in-out;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: bold;
        font-size: 13px;
        color: white;
    }

    .badge-refunded {
        background: #6f42c1;
        /* purple */
    }


    .badge-pending {
        background: #ffc107;
        color: black;
    }

    .badge-processing {
        background: #17a2b8;
    }

    .badge-shipped {
        background: #007bff;
    }

    .badge-completed {
        background: #28a745;
    }

    .badge-cancelled {
        background: #dc3545;
    }

    table tbody tr:hover {
        background: #f9f9f9;
        cursor: pointer;
    }
</style>
@endpush

{{-- JS --}}
@push('scripts')
<script src="{{ asset('js/sweetalert.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const flash = document.getElementById("order-flash");

        const successMessage = flash.dataset.success;
        const errorMessage = flash.dataset.error;

        if (successMessage) {
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: successMessage,
                showConfirmButton: false,
                timer: 2500
            });
        }

        if (errorMessage) {
            Swal.fire({
                icon: "error",
                title: "Error Occurred!",
                text: errorMessage,
                confirmButtonColor: "#dc3545"
            });
        }

    });
</script>
@endpush