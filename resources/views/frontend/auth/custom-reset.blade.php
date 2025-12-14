@extends('frontend.layouts.app')

@section('content')
<div class="container py-5">
    <h2>Create New Password</h2>

    <form method="POST" action="{{ route('custom.reset.save', $user->id) }}">
        @csrf

        <div>
            <label>New Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mt-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button class="btn btn-success mt-4">Reset Password</button>
    </form>
</div>
@endsection
