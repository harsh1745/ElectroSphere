    @extends('admin.layouts.admin')

    @section('title', 'Manage All Orders')

    @push('styles')
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --primary: #DB4444;
            --primary-light: #ff5c5c;
            --border: #e5e7eb;
            --text-dark: #1f1f1f;
        }

        table.dataTable {
            background: #ffffff !important;
            color: var(--text-dark);
            border-radius: 12px;
            overflow: hidden !important;
            width: 100%;
        }

        thead th {
            background: #f8f8f8 !important;
            border-bottom: 2px solid var(--primary) !important;
            font-weight: 700;
        }

        table.dataTable tbody tr:hover {
            background-color: #ffecec !important;
        }

        /* Sorting arrow color */
        table.dataTable thead th.sorting:after,
        table.dataTable thead th.sorting_asc:after,
        table.dataTable thead th.sorting_desc:after {
            color: var(--primary) !important;
        }

        /* Search input */
        .dataTables_filter input {
            background: #fff !important;
            border: 2px solid var(--primary) !important;
            border-radius: 12px !important;
            padding: 10px 14px !important;
            outline: none !important;
            width: 260px !important;
            transition: 0.25s;
        }

        .dataTables_filter input:focus {
            box-shadow: 0 0 0 4px rgba(219, 68, 68, 0.25) !important;
        }

        /* Pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid var(--border) !important;
            padding: 6px 12px;
            border-radius: 6px;
            background: white !important;
            color: var(--text-dark) !important;
            margin: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #ffe5e5 !important;
            border-color: var(--primary) !important;
            color: var(--primary) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: var(--primary) !important;
            border: 1px solid var(--primary) !important;
            color: white !important;
        }

        /* Length Dropdown */
        .dataTables_length select {
            border: 2px solid var(--primary) !important;
            border-radius: 8px;
            padding: 5px;
        }

        .btn-danger-color {
            background-color: #db4444;
            color: white;
        }

        .btn-danger-color:hover {
            background-color: #db4444cf !important;
            color: white !important;
        }
    </style>
    @endpush



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
                    <table id="ordersTable" class="display" style="width:100%">
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
                                <td>₹{{ number_format($order->total_amount, 2) }}</td>

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
                                    <span class="badge bg-{{ $badge }}">{{ $order->status }}</span>
                                </td>

                                <td data-order="{{ $order->created_at->timestamp }}">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-danger-color">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
        @endif
    </div>
    @endsection





    @push('scripts')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10,
                ordering: true,
                searching: true,
                paging: true,
                order: [
                    [4, "desc"]
                ], // SORT BY DATE DESC
                language: {
                    search: "",
                    searchPlaceholder: "Search orders...",
                    lengthMenu: "_MENU_ per page"
                }
            });

        });
    </script>
    @endpush