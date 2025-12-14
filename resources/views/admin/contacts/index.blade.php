@extends('admin.layouts.admin')

@section('title', 'Contact Messages')

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

    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        color: var(--primary) !important;
    }

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

    .btn-view {
        background-color: var(--primary);
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-view:hover {
        background-color: var(--primary-light);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid" style="margin-top: 2rem;">

    <h1 class="h3 mb-4 text-gray-800">Contact Messages</h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table id="contactTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Subject</th>
                            <!-- <th>Message</th> -->
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($messages as $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>{{ $m->first_name }} {{ $m->last_name }}</td>
                            <td>{{ $m->email }}</td>
                            <td>{{ $m->phone }}</td>
                            <td>{{ $m->subject }}</td>
                            <!-- <td>{{ Str::limit($m->message, 40) }}</td> -->
                            <td data-order="{{ $m->created_at->timestamp }}">
                                {{ $m->created_at->format('d M Y') }}
                            </td>

                            <td>
                                <button class="btn btn-view"
                                    data-bs-toggle="modal"
                                    data-bs-target="#msgModal{{ $m->id }}">
                                    View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>


                </table>
            </div>
            @foreach ($messages as $m)
            <div class="modal fade" id="msgModal{{ $m->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">

                        <div class="modal-header" style="border-bottom: 2px solid #DB4444;">
                            <h5 class="modal-title fw-bold">
                                Message from {{ $m->first_name }} {{ $m->last_name }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <p><strong>Email:</strong> {{ $m->email }}</p>
                            <p><strong>Phone:</strong> {{ $m->phone }}</p>
                            <p><strong>Subject:</strong> {{ $m->subject }}</p>

                            <hr>

                            <p class="mb-0"><strong>Message:</strong></p>
                            <p style="white-space: pre-wrap;">{{ $m->message }}</p>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#contactTable').DataTable({
            dom: '<"top"lf>rt<"bottom"ip>',
            pageLength: 10,
            ordering: true,
            searching: true,
            paging: true,
            order: [
                [6, "desc"]
            ], // sort by date
            language: {
                search: "",
                searchPlaceholder: "Search messages...",
                lengthMenu: "_MENU_ per page"
            }
        });
    });
</script>
@endpush