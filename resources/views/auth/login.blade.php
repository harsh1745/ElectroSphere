@extends('frontend.layouts.app')

@section('content')
<style>
    /* Custom styles to match the screenshot layout */
    .auth-container {
        max-width: 1100px;
        /* Container width jaisa screenshot mein hai */
        margin: 0 auto;
        border: 1px solid #e5e5e5;
        /* Light border around the main container */
        min-height: 600px;
        /* Minimum height for visual stability */
    }

    /* Left image/banner side */
    .auth-image-side {
        background-color: #f0f8ff;
        /* Light blue background */
        /* Note: Background image CSS ko remove kar diya hai aur sirf <img> tag use kiya hai */
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        /* Padding remove kiya taaki image full width/height le */
        overflow: hidden;
        /* Ensure image fits boundary */
    }

    .auth-image-side img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Image ko container mein cover karne ke liye */
        display: block;
    }

    /* Right form side */
    .auth-form-side {
        padding: 60px 80px;
        /* Padding jaisa screenshot mein hai */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .form-control {
        border: none;
        border-bottom: 1px solid #e5e5e5;
        /* Only bottom border */
        border-radius: 0;
        padding-left: 0;
        padding-right: 0;
        height: 45px;
        margin-bottom: 30px;
        /* Spacing between fields */
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #000;
        /* Focus border black */
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
    }

    .login-btn:hover {
        background-color: #c13434;
        color: white;
    }

    .forgot-password {
        color: #DB4444;
        /* Red link color */
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    .forgot-password:hover {
        color: #c13434;
    }

    /* Style for the new 'Register' link */
    .register-link {
        color: #DB4444;
        /* Red color for Register link */
        font-weight: 600;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .register-link:hover {
        opacity: 0.8;
        color: #c13434;
    }

    /* --- NEW POPUP STYLES --- */
    #status-popup {
        position: fixed;
        /* Fixed position for popup effect */
        top: 50px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1050;
        /* Ensure it is above everything else */
        width: 90%;
        max-width: 450px;
        opacity: 1;
        /* Start visible */
        transition: opacity 1s ease-in-out;
        /* Smooth fade-out transition */
    }

    .alert-success-custom {
        background-color: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
        padding: 15px;
        border-radius: 4px;
        /* margin-bottom: 25px; - Removed margin as it's a fixed popup now */
        font-weight: 500;
        border: 1px solid;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        /* Added subtle shadow */
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .auth-container {
            border: none;
        }

        .auth-image-side {
            min-height: 300px;
            display: none;
            /* Mobile pe image hide kar diya */
        }

        .auth-form-side {
            padding: 40px 20px;
        }
    }
</style>

<!-- --- POPUP MESSAGE BLOCK START (Moved to outside the container for fixed position) --- -->
@if (session('status'))
<div id="status-popup">
    <div class="alert-success-custom" role="alert">
        {{ session('status') }}
    </div>
</div>
@endif
<!-- --- POPUP MESSAGE BLOCK END --- -->


<div class="container py-5">
    <div class="row auth-container mx-auto bg-white">
        <!-- Left Side: Image/Banner -->
        <div class="col-lg-6 auth-image-side">
            <!-- 
                Image URL updated to use asset() helper.
                Please ensure your image is located at: 
                public/images/login.jpeg 
            -->
            <img src="{{ asset('images/login.jpeg') }}"
                alt="Exclusive Shopping Banner"
                class="img-fluid"
                onerror="this.onerror=null;this.style.display='none';"
                style="height: 100%; width: 100%; object-fit: contain; background:white;">
        </div>

        <!-- Right Side: Login Form -->
        <div class="col-lg-6 auth-form-side">

            <h2 class="mb-2 fw-bold">Log in to Exclusive</h2>
            <p class="mb-5 text-muted">Enter your details below</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email or Phone Field -->
                <div class="mb-3">
                    <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        placeholder="Email or Phone Number">

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password"
                        placeholder="Password">

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <!-- Login Button & Forgot Password Link -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn login-btn flex-grow-1 me-3">
                        {{ __('Log In') }}
                    </button>

                    @if (Route::has('password.request'))
                    <a class="forgot-password text-nowrap" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                    @endif
                </div>

                <!-- New: Register Link -->
                <div class="text-center mt-5">
                    <span class="text-muted">Don't have an account?</span>
                    <a class="register-link ms-2" href="{{ route('register') }}">Register</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // JavaScript to automatically hide the success message after 2 seconds
    document.addEventListener('DOMContentLoaded', function() {
        // Target the new fixed popup container by its ID
        const alertElement = document.getElementById('status-popup');

        // Check if the alert is present (i.e., if session('status') was set)
        if (alertElement) {
            // Wait for 2000 milliseconds (2 seconds)
            setTimeout(function() {
                // Start fade out transition by setting opacity to 0
                alertElement.style.opacity = '0';

                // Remove the element from the DOM after the 1-second CSS transition finishes
                setTimeout(function() {
                    alertElement.remove();
                }, 1000);
            }, 2000);
        }
    });
</script>
@endsection