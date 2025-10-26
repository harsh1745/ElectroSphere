{{-- resources/views/admin/users/index.blade.php --}}

@extends('admin.layouts.admin')

@section('content')
<h1>Customer Users</h1>

{{-- Success/Warning Messages (from verification action) --}}
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('warning'))
<div class="alert alert-warning">{{ session('warning') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>DOB</th>
            <th>Registered On</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            {{-- NAME PAR CLICK KARNE PAR MODAL OPEN HOGA --}}
            <td>
                <a href="#" class="view-user-details"
                    data-user-id="{{ $user->id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#userDetailsModal">
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
                {{-- Agar tumhara CAPTCHA fail ho gaya ya tumne seedha database mein dala --}}
                @endif
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

{{-- User Details Modal (No change needed here) --}}
<div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userDetailsModalLabel">User Details: <span id="modalUserName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Email:</strong> <span id="modalUserEmail"></span></p>
                <p><strong>DOB:</strong> <span id="modalUserDob"></span></p>
                <p><strong>Verified At:</strong> <span id="modalUserVerified"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // User Name par click hone par
        $('.view-user-details').on('click', function(e) {
            e.preventDefault();

            var userId = $(this).data('user-id');
            var url = "{{ route('admin.users.show', ':id') }}";
            url = url.replace(':id', userId);

            // AJAX call karke user details fetch karna
            $.ajax({
                url: url,
                method: 'GET',
                success: function(user) {
                    // Modal mein data fill karna
                    $('#modalUserName').text(user.name);
                    $('#modalUserEmail').text(user.email);
                    $('#modalUserDob').text(user.date_of_birth || 'Not specified');

                    // Dates ko clean format mein dikhana
                    // $('#modalUserCreated').text(new Date(user.created_at).toLocaleDateString());
                    // $('#modalUserUpdated').text(new Date(user.updated_at).toLocaleDateString());

                    // Modal show karna
                    $('#userDetailsModal').modal('show');
                    // Verified status check
                    if (user.email_verified_at) {
                        // Date ko clean format mein dikhana agar verified hai
                        var verifiedDate = new Date(user.email_verified_at).toLocaleDateString();
                        $('#modalUserVerified').html('<span class="text-success">' + verifiedDate + '</span>');
                    } else {
                        $('#modalUserVerified').html('<span class="text-danger">Not verified</span>');
                    }
                },
                error: function() {
                    alert('Could not fetch user details.');
                }
            });
        });
    });
</script>
@endpush