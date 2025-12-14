@extends('admin.layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    :root {
        --primary: #DB4444;
        --primary-light: #ff5c5c;
        --text-dark: #1f1f1f;
        --bg-light: #ffffff;
        --border: #e5e7eb;
    }

    /* Table Container */
    table.dataTable {
        background: var(--bg-light) !important;
        color: var(--text-dark) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
    }

    /* Header */
    table.dataTable thead th {
        background: #f8f8f8 !important;
        color: #333 !important;
        font-weight: 700;
        border-bottom: 2px solid var(--primary) !important;
    }

    /* Sorting Arrow Color */
    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        color: var(--primary) !important;
        opacity: 1 !important;
    }

    /* Rows */
    table.dataTable tbody tr {
        background: #ffffff !important;
    }

    table.dataTable tbody tr:hover {
        background: #ffecec !important;
        /* light red hover */
    }

    /* Search box */
    .dataTables_filter input {
        border: 1px solid var(--primary);
        padding: 6px 10px;
        border-radius: 6px;
        outline: none;
    }

    .dataTables_filter input:focus {
        box-shadow: 0 0 0 2px rgba(219, 68, 68, 0.25);
    }

    /* Page length dropdown */
    .dataTables_length select {
        border: 1px solid var(--primary);
        padding: 5px 8px;
        border-radius: 6px;
    }

    /* Pagination Buttons */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: white !important;
        border: 1px solid var(--border) !important;
        border-radius: 6px !important;
        color: var(--text-dark) !important;
        margin: 2px;
        padding: 5px 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ffe0e0 !important;
        border: 1px solid var(--primary) !important;
        color: var(--primary) !important;
    }

    /* Active Page */
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary) !important;
        border: 1px solid var(--primary) !important;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: var(--primary-light) !important;
        border-color: var(--primary-light) !important;
    }

    /* Info Text */
    .dataTables_info {
        color: #555 !important;
    }

    /* BEAUTIFUL PREMIUM SEARCH INPUT - RED THEME */
    .dataTables_filter {
        margin-bottom: 12px !important;
    }

    .dataTables_filter input {
        background: #ffffff !important;
        border: 2px solid #DB4444 !important;
        color: #333 !important;
        padding: 10px 14px !important;
        border-radius: 12px !important;
        /* BIG ROUNDED */
        font-size: 14px;
        transition: 0.25s ease-in-out;
        width: 240px !important;
    }

    .dataTables_filter input::placeholder {
        color: #b98b8b !important;
        /* soft themed placeholder */
        opacity: 1;
    }

    /* Hover */
    .dataTables_filter input:hover {
        border-color: #e95a5a !important;
    }

    /* Focus */
    .dataTables_filter input:focus {
        border-color: #DB4444 !important;
        box-shadow: 0 0 0 4px rgba(219, 68, 68, 0.25) !important;
        outline: none !important;
    }

    /* Remove default search label text spacing */
    .dataTables_filter label {
        color: #333 !important;
        font-weight: 600;
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

<x-admin.back />

<h1 class="mb-3">Product Catalog</h1>

<a href="{{ route('admin.products.create') }}" class="btn btn-danger-color mb-3">
    ➕ Add New Product
</a>

<div class="table-responsive">
    <table id="productsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category Name</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>₹{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock ?? 0 }}</td>
                <td>{{ $product->category->name ?? 'N/A' }}</td>
                <td>{{ $product->created_at->format('d-m-Y') }}</td>
                <td>{{ $product->updated_at->format('d-m-Y') }}</td>

                <td>
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                        class="btn btn-sm btn-danger">Edit</a>

                    <form action="{{ route('admin.products.destroy', $product->id) }}"
                        method="POST" style="display:inline-block;">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                            onclick="return confirm('Delete this product?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

@endsection



@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#productsTable').DataTable({
            dom: '<"top"lf>rt<"bottom"ip>',
            pageLength: 10,
            lengthChange: true,
            searching: true,
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                orderable: false,
                targets: -1
            }],
            language: {
                search: "",
                searchPlaceholder: "Search products...",
                lengthMenu: "_MENU_ per page"
            }
        });
    });
</script>
@endpush