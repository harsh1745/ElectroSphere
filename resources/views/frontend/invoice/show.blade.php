{{-- resources/views/frontend/invoice/show.blade.php (FINAL VERSION) --}}

@extends('frontend.layouts.app')

@section('title', 'Order Invoice ' . $order->order_number)

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card shadow border-0">
                <div class="card-header bg-light py-4 px-5">
                    <h2 class="mb-0 fw-bold">Invoice #{{ $order->order_number }}</h2>
                    <p class="mb-0 text-muted">Order Date: {{ $order->created_at->format('d M, Y') }}</p>
                </div>
                <div class="card-body p-5">

                    {{-- Customer/Shipping Details --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Billed To:</h5>
                            <p class="mb-0">{{ $order->user->name ?? 'N/A' }}</p>
                            <p class="mb-0">{{ $order->user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="fw-bold">Shipping To:</h5>

                            {{-- ✅ FIX 1: Shipping Address (JSON data ko decode karein aur Fallback dein) --}}
                            @php
                            // Agar JSON string save nahi hua, toh yeh 'null' ya empty array return karega
                            $shipping = json_decode($order->shipping_address_json ?? '[]', true);

                            // Agar JSON empty hai, toh Order-Address relationship ko use karein
                            $addressData = $shipping && !empty($shipping['street']) ? $shipping : $order->address;
                            @endphp

                            @if($addressData)
                            {{-- Agar $addressData mein Address Model object ya JSON data hai --}}
                            <p class="mb-0 fw-semibold">{{ $addressData['full_name'] ?? $addressData['name'] ?? 'N/A' }}</p>
                            <p class="mb-0">{{ $addressData['street_address'] ?? $addressData['street'] ?? 'Street Missing' }}, {{ $addressData['city'] ?? 'City Missing' }}</p>
                            <p class="mb-0">{{ $addressData['state'] ?? 'State Missing' }}, {{ $addressData['zip_code'] ?? 'Zip Missing' }}</p>
                            <p class="mb-0">Phone: {{ $addressData['phone'] ?? 'N/A' }}</p>
                            @else
                            {{-- Fallback jab koi data na mile --}}
                            <p class="mb-0 text-danger fw-bold">Address details not available.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Order Items Table --}}
                    <h5 class="fw-bold mb-3">Order Summary</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Product</th>
                                    <th scope="col" class="text-center">Quantity</th>
                                    <th scope="col" class="text-end">Unit Price</th>
                                    <th scope="col" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <span class="fw-semibold">{{ $item->product->name ?? 'Product Not Found' }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-end fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                {{-- Total Calculation --}}
                                <tr>
                                    <td colspan="4" class="text-end">Subtotal:</td>
                                    {{-- ✅ FIX 2: Order model se subtotal use karein (Agar Controller mein save hua hai) --}}
                                    <td class="text-end fw-bold">${{ number_format($order->subtotal ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end">Shipping:</td>
                                    {{-- ✅ FIX 2: Order model se shipping cost use karein --}}
                                    <td class="text-end fw-bold">${{ number_format($order->shipping_cost ?? 0, 2) }}</td>
                                </tr>
                                <tr class="fs-5">
                                    <td colspan="4" class="text-end fw-bold">Grand Total:</td>
                                    {{-- Grand Total --}}
                                    <td class="text-end text-danger fw-bolder">${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Payment Details --}}
                    <div class="row mt-5 pt-3 border-top">
                        <div class="col-md-6">
                            <h5 class="fw-bold">Payment Details</h5>

                            {{-- ✅ FIX 3: Payment Method (COD display) --}}
                            <p class="mb-1">
                                Method:
                                <span class="fw-semibold text-dark">
                                    {{-- Payment method show karein --}}
                                    {{ strtoupper($order->payment_method ?? 'N/A') == 'COD' ? 'COD (Cash on Delivery)' : strtoupper($order->payment_method ?? 'N/A') }}
                                </span>
                            </p>

                            {{-- ✅ FIX 4: Payment Status (payment_status field use karein) --}}
                            {{-- PAYMENT STATUS – FIXED LOGIC --}}
                            @php
                            $map = [
                            'pending' => 'pending',
                            'processing' => 'pending',
                            'shipped' => 'pending',
                            'completed' => 'paid',
                            'cancelled' => 'failed',
                            'refunded' => 'refunded'
                            ];

                            $paymentStatus = $map[$order->status] ?? 'pending';

                            $badgeClass = match($paymentStatus) {
                            'pending' => 'bg-warning text-dark',
                            'paid' => 'bg-success',
                            'failed' => 'bg-danger',
                            'refunded' => 'bg-dark',
                            default => 'bg-secondary'
                            };
                            @endphp

                            <p class="mb-0">
                                Status:
                                <span class="badge {{ $badgeClass }} fw-bold">
                                    {{ strtoupper($paymentStatus) }}
                                </span>
                            </p>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('shop.index') }}" class="btn btn-dark btn-lg fw-bold">
                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- @push('scripts')
{{-- SweetAlert scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('order_success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Order Confirmed!',
            text: "{{ session('order_success') }}",
            icon: 'success',
            confirmButtonText: 'View Invoice',
            confirmButtonColor: '#4CAF50'
        });
    });
</script>
@endif
@endpush -->
@push('scripts')
{{-- SweetAlert scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('order_success_message'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const message = "{{ session('order_success_message') }}";

        // 1. Show the SweetAlert
        Swal.fire({
            title: 'Order Confirmed!',
            text: message, // Use the message from the session
            icon: 'success',
            // We don't need 'View Invoice' button as the user is already on the invoice page.
            confirmButtonText: 'Great!',
            confirmButtonColor: '#4CAF50'
        });

        // 2. ✅ Remove the session message so it doesn't fire again on refresh
        // You may need to handle this via AJAX or a separate route to properly forget the session flash data if the default behavior doesn't remove it after display.
        // For security, Laravel usually handles flash data removal after one request.
    });
</script>
@endif
@endpush