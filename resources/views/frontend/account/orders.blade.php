@extends('frontend.layouts.app')

@section('content')

<div class="container my-5">

    <h2 class="mb-4">My Orders</h2>

    @if($orders->isEmpty())
    <p>No orders found.</p>
    @else
    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>#ID</th>
                <th>Total</th>
                <th>Status</th>
                <th>Placed On</th>
            </tr>
        </thead>

        <tbody>
            @foreach($orders as $order)

            @php
            // BADGE COLOR LOGIC
            $badge = match($order->status) {
            'pending' => 'bg-warning text-dark',
            'processing' => 'bg-primary',
            'shipped' => 'bg-info text-dark',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
            'refunded' => 'bg-dark',
            default => 'bg-secondary'
            };
            @endphp

            <tr>
                <td>{{ $order->id }}</td>

                {{-- Correct Total Column --}}
                <td>${{ number_format($order->total_amount, 2) }}</td>

                {{-- Status Badge --}}
                <td>
                    <span class="badge {{ $badge }}">
                        {{ strtoupper($order->status) }}
                    </span>
                </td>

                {{-- Order Date --}}
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>

            @endforeach
        </tbody>
    </table>
    @endif

</div>

@endsection