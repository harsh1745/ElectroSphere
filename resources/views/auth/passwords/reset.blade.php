@extends('frontend.layouts.app')

@section('content')
<style>
    /* Same custom styles as Login/Register pages */
    .auth-container {
        max-width: 1100px;
        margin: 0 auto;
        border: 1px solid #e5e5e5;
        min-height: 600px;
    }

    .auth-image-side {
        background-color: #f0f8ff;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        overflow: hidden;
    }

    .auth-image-side img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .auth-form-side {
        padding: 60px 80px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-control {
        border: none;
        border-bottom: 1px solid #e5e5e5;
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        height: 45px;
        margin-bottom: 30px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #000;
    }

    .reset-btn {
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

    .reset-btn:hover {
        background-color: #c13434;
        color: white;
    }

    /* Message for success/error */
    .alert-success-custom {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 25px;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .auth-container {
            border: none;
        }

        .auth-image-side {
            min-height: 300px;
            display: none;
        }

        .auth-form-side {
            padding: 40px 20px;
        }
    }
</style>

<div class="container py-5">
    <div class="row auth-container mx-auto bg-white">
        <!-- Left Side: Image/Banner -->
        <div class="col-lg-6 auth-image-side">
            <!-- Image asset from public/images/register.jpg (Re-using the same style) -->
            <img src="{{ asset('images/login.jpeg') }}"
                alt="Exclusive Password Reset Banner"
                class="img-fluid"
                onerror="this.onerror=null;this.style.display='none';"
                style="height: 100%; width: 100%; object-fit:  contain;background:white;">
        </div>

        <!-- Right Side: Reset Password Form -->
        <div class="col-lg-6 auth-form-side">
            <h2 class="mb-2 fw-bold">Set New Password</h2>
            <p class="mb-5 text-muted">Enter your email and new password to complete the process.</p>

            @if (session('status'))
            <div class="alert-success-custom mb-4" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                {{-- **IMPORTANT:** The token field has been removed for the custom DOB verification flow. --}}

                <!-- Email Field (Pre-filled from controller) -->
                <div class="mb-3">
                    {{-- $email variable ResetPasswordController se aayega --}}
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus readonly
                        placeholder="Email Address" title="This field is read-only as it was verified with your Date of Birth.">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="new-password"
                        placeholder="New Password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-3">
                    <input id="password-confirm" type="password" class="form-control"
                        name="password_confirmation" required autocomplete="new-password"
                        placeholder="Confirm New Password">
                </div>

                <!-- Reset Password Button -->
                <button type="submit" class="btn reset-btn mt-4 mb-4">
                    {{ __('Reset Password') }}
                </button>
            </form>

            <div class="text-center mt-3">
                <a class="text-muted" href="{{ route('login') }}">Back to Log In</a>
            </div>
        </div>
    </div>
</div>
@endsection