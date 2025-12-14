@extends('frontend.layouts.app')

@section('content')
<div class="container">
    <h2>Edit My Account</h2>

    <form action="{{ route('account.update') }}" method="POST">
        @csrf

        <div class="form-group mt-2">
            <label>Name</label>
            <input type="text" name="name" class="form-control"
                value="{{ $user->name }}" required>
        </div>

        <div class="form-group mt-2">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" class="form-control"
                value="{{ $user->date_of_birth }}">
        </div>

        <div class="form-group mt-2">
            <label>Current Password</label>
            <input type="password" name="current_password" class="form-control">
            <small class="text-muted">
                Don’t remember your password?
                <a href="{{ route('custom.forgot') }}">Forgot Password</a>
            </small>
        </div>

        <div class="form-group mt-2">
            <label>New Password</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="form-group mt-2">
            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>


        <button class="btn btn-success mt-3">Save Changes</button>
        <a href="{{ route('account') }}" class="btn btn-secondary mt-3">Cancel</a>
    </form>
</div>
@endsection