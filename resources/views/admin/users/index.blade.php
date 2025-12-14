@extends('admin.layouts.admin')

@section('content')

@push('styles')
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">


<style>
    :root {
        --primary: #DB4444;
        --primary-light: #ff5c5c;
        --border: #e5e7eb;
        --text: #1f1f1f;
    }

    table.dataTable {
        background: #ffffff !important;
        border-radius: 12px;
        overflow: hidden;
        width: 100%;
    }

    table.dataTable thead th {
        background: #f8f8f8 !important;
        border-bottom: 2px solid var(--primary) !important;
        font-weight: 700;
    }

    table.dataTable tbody tr:hover {
        background-color: #ffecec !important;
    }

    /* Sorting arrows */
    table.dataTable thead th.sorting:after,
    table.dataTable thead th.sorting_asc:after,
    table.dataTable thead th.sorting_desc:after {
        color: var(--primary) !important;
    }

    /* Search box */
    .dataTables_filter input {
        border: 2px solid var(--primary) !important;
        padding: 10px 14px !important;
        border-radius: 12px !important;
        outline: none !important;
        width: 260px !important;
        transition: .25s;
    }

    .dataTables_filter input:focus {
        box-shadow: 0 0 0 4px rgba(219, 68, 68, 0.25) !important;
    }

    /* Pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1px solid var(--border);
        padding: 6px 12px;
        border-radius: 6px;
        background: #fff !important;
        color: #333 !important;
        margin: 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #ffe5e5 !important;
        border-color: var(--primary);
        color: var(--primary) !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--primary) !important;
        color: #fff !important;
        border: 1px solid var(--primary);
    }

    /* Page length dropdown */
    .dataTables_length select {
        border: 2px solid var(--primary) !important;
        border-radius: 8px;
        padding: 5px;
    }
</style>
@endpush

<x-admin.back />

<h1>Customer Users</h1>

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('warning'))
<div class="alert alert-warning">{{ session('warning') }}</div>
@endif

<div class="table-responsive">
    <table id="usersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name (View Details)</th>
                <th>Email</th>
                <th>DOB</th>
                <th>Registered</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>

                <td>
                    <a href="#" class="view-user-details"
                        data-user-id="{{ $user->id }}"
                        data-bs-toggle="modal"
                        data-bs-target="#userDetailsModal"
                        style="color: var(--primary); font-weight:600;">
                        {{ $user->name }}
                    </a>
                </td>

                <td>{{ $user->email }}</td>
                <td>{{ $user->date_of_birth ?? 'N/A' }}</td>
                <td>{{ $user->created_at->format('d M Y') }}</td>

                <td>
                    @if ($user->email_verified_at)
                    <span class="badge bg-success">Verified</span>
                    @else
                    <span class="badge bg-warning text-dark">Unverified</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


{{-- USER DETAILS MODAL (unchanged) --}}
<div class="modal fade" id="userDetailsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">User Details: <span id="modalUserName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p><strong>Email:</strong> <span id="modalUserEmail"></span></p>
                <p><strong>DOB:</strong> <span id="modalUserDob"></span></p>
                <p><strong>Verified At:</strong> <span id="modalUserVerified"></span></p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>

@endsection



{{-- DATATABLE + AJAX SCRIPT --}}
@push('scripts')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {

        // Initialize DataTable
        $('#usersTable').DataTable({
            dom: '<"top"lf>rt<"bottom"ip>',
            ordering: true,
            searching: true,
            pageLength: 10,
            order: [
                [0, 'desc']
            ],
            language: {
                search: "",
                searchPlaceholder: "Search users...",
                lengthMenu: "_MENU_ per page"
            }
        });

        // AJAX Modal Loader
        $('.view-user-details').on('click', function(e) {
            e.preventDefault();

            let userId = $(this).data('user-id');
            let url = "{{ route('admin.users.show', ':id') }}".replace(':id', userId);

            $.ajax({
                url: url,
                method: "GET",
                success: function(user) {
                    $('#modalUserName').text(user.name);
                    $('#modalUserEmail').text(user.email);
                    $('#modalUserDob').text(user.date_of_birth ?? 'N/A');

                    if (user.email_verified_at) {
                        $('#modalUserVerified').html('<span class="text-success">' + new Date(user.email_verified_at).toLocaleDateString() + '</span>');
                    } else {
                        $('#modalUserVerified').html('<span class="text-danger">Not Verified</span>');
                    }

                    $('#userDetailsModal').modal('show');
                },
                error: function() {
                    alert('Unable to load details.');
                }
            });
        });

    });
</script>
@endpush