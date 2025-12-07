@extends('frontend.layouts.app')

@section('content')

<div class="container my-5">

    <h2 class="mb-4">My Invoices</h2>

    @if($invoices->isEmpty())
    <p>No invoices available.</p>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#Invoice ID</th>
                <th>Order ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Download</th>
            </tr>
        </thead>

        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->order_id }}</td>
                <td>${{ number_format($invoice->total, 2) }}</td>
                <td>{{ $invoice->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ asset('storage/invoices/' . $invoice->file) }}" target="_blank"
                        class="btn btn-sm btn-primary">
                        Download
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

</div>

@endsection