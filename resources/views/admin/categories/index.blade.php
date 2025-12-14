@extends('admin.layouts.admin')

{{-- ================= CUSTOM RED-THEME DATATABLE STYLES ================= --}}
@push('styles')
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">

<style>
    :root {
        --primary: #DB4444;
        --primary-light: #ff5c5c;
        --border: #e5e7eb;
        --text-dark: #1f1f1f;
    }

    /* Table Layout */
    table.dataTable {
        background: #fff !important;
        color: var(--text-dark) !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        width: 100% !important;
    }

    table.dataTable thead th {
        background: #f8f8f8 !important;
        font-weight: 700;
        border-bottom: 2px solid var(--primary) !important;
    }

    table.dataTable tbody tr:hover {
        background-color: #ffeaea !important;
    }

    /* Sorting arrows */
    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        color: var(--primary) !important;
    }

    /* Search Input (Premium Rounded) */
    .dataTables_filter input {
        background: #ffffff !important;
        border: 2px solid var(--primary) !important;
        padding: 10px 14px !important;
        border-radius: 12px !important;
        outline: none !important;
        width: 240px !important;
        font-size: 14px;
        transition: 0.25s ease;
    }

    .dataTables_filter input:focus {
        box-shadow: 0 0 0 4px rgba(219, 68, 68, 0.25) !important;
    }

    .dataTables_filter label {
        font-weight: 600;
        color: #333;
    }

    /* Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid var(--border) !important;
        border-radius: 6px !important;
        background: white !important;
        padding: 6px 12px !important;
        margin: 2px !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ffe5e5 !important;
        border-color: var(--primary) !important;
        color: var(--primary) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary) !important;
        color: white !important;
        border: 1px solid var(--primary) !important;
    }

    /* Dropdown page length */
    .dataTables_length select {
        border: 2px solid var(--primary) !important;
        border-radius: 8px !important;
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

<h1 class="mb-3">Category List</h1>

<a href="{{ route('admin.categories.create') }}" class="btn btn-danger-color mb-3">
    ➕ Add New Category
</a>

<div class="table-responsive">
    <table id="categoriesTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection


{{-- ================= DATATABLE SCRIPT ================= --}}
@push('scripts')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

<script>
    $(document).ready(function() {

        $('#categoriesTable').DataTable({
            dom: '<"top"lf>rt<"bottom"ip>',
            pageLength: 10,
            lengthChange: true,
            ordering: true,
            searching: true,
            paging: true,
            order: [
                [0, 'desc']
            ], // Sort by ID
            language: {
                search: "",
                searchPlaceholder: "Search categories...",
                lengthMenu: "_MENU_ per page"
            }
        });

    });
</script>
@endpush