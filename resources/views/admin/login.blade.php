@extends('admin.layouts.admin_auth')
@section('content')
<style>
    /* Custom styles for Admin Login - Simple, centered layout */
    .admin-auth-container {
        max-width: 450px;
        /* Thoda chhota aur focused container */
        margin: 50px auto;
        padding: 40px;
        background-color: #fff;
        /* White background */
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .form-control {
        border: 1px solid #ccc;
        border-radius: 4px;
        height: 45px;
        margin-bottom: 20px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #DB4444;
        /* Red accent on focus */
    }

    .login-btn {
        background-color: #DB4444;
        /* Red button color */
        border: none;
        color: white;
        padding: 12px;
        font-weight: 500;
        border-radius: 4px;
        transition: background-color 0.3s;
        width: 100%;
    }

    .login-btn:hover {
        background-color: #c13434;
        color: white;
    }

    .alert-danger-custom {
        background-color: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="admin-auth-container">
        <h2 class="mb-4 text-center fw-bold text-dark">Admin Login</h2>
        <p class="mb-4 text-center text-muted">Use your administrative credentials.</p>

        {{-- Validation Errors and Failed Attempts --}}
        @if ($errors->any())
        <div class="alert-danger-custom">
            @foreach ($errors->all() as $error)
            {{ $error }}<br>
            @endforeach
        </div>
        @endif

        {{-- Login Form --}}
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            {{-- Email Field --}}
            <div class="mb-3">
                <input id="email" type="email" class="form-control"
                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                    placeholder="Email Address">
            </div>

            {{-- Password Field --}}
            <div class="mb-3">
                <input id="password" type="password" class="form-control"
                    name="password" required autocomplete="current-password"
                    placeholder="Password">
            </div>

            {{-- Remember Me & Login Button --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label small text-muted" for="remember">
                        Remember Me
                    </label>
                </div>
            </div>

            <button type="submit" class="btn login-btn">
                {{ __('Log In as Admin') }}
            </button>

            {{-- Optional: Back to Site Link --}}
            <div class="text-center mt-4">
                <a class="text-muted small" href="{{ route('home') ?? url('/') }}">← Back to Customer Site</a>
            </div>
        </form>
    </div>
</div>
@endsection