{{-- resources/views/frontend/account/invoices/index.blade.php (CLEANED AND FINALIZED) --}}

@extends('frontend.layouts.app')

@section('title', 'My Invoices')

@section('content')

<div class="container my-5">
    <h2 class="mb-4 fw-bold">My Orders</h2>
    <p class="text-muted">View your order history and invoices here.</p>

    {{-- START OF THE PRIMARY CONDITIONAL BLOCK --}}
    @if($invoices->isEmpty())
    <div class="alert alert-info text-center py-4">
        You have no previous orders yet.
    </div>
    @else
    {{-- Display the table if invoices exist --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">

            {{-- Table Headers --}}
            <thead class="table-light">
                <tr>
                    <th>Order #</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($invoices as $order)
                <tr>
                    {{-- 1. Order Number --}}
                    <td>{{ $order->order_number ?? $order->id }}</td>

                    {{-- 2. Date --}}
                    <td>{{ $order->created_at->format('d M, Y') }}</td>

                    {{-- 3. Total Amount --}}
                    <td>₹{{ number_format($order->total_amount, 2) }}</td>

                    {{-- 4. Payment Method --}}
                    <td>{{ strtoupper($order->payment_method ?? 'N/A') }}</td>

                    {{-- 5. Status --}}
                    <td>
                        @php
                        $badgeColor = match($order->status) {
                        'completed' => 'bg-success',
                        'shipped' => 'bg-info',
                        'processing' => 'bg-primary',
                        'pending' => 'bg-warning text-dark',
                        'cancelled' => 'bg-danger',
                        'refunded' => 'bg-dark',
                        default => 'bg-secondary',
                        };

                        @endphp

                        <span class="badge {{ $badgeColor }} text-uppercase">
                            {{ $order->status }}
                        </span>
                    </td>

                    {{-- 6. Actions (View Link) --}}
                    <td>
                        <a href="{{ route('invoice.show', ['order_id' => $order->id]) }}"
                            class="btn btn-sm btn-primary">
                            View Invoice
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    {{-- END OF THE PRIMARY CONDITIONAL BLOCK --}}

</div>

@endsection