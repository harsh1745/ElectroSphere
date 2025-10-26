    @extends('frontend.layouts.app')

    @section('content')
    <style>
        /* Custom styles to match your theme */
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

        .register-btn {
            background-color: #DB4444;
            border: none;
            color: white;
            padding: 12px;
            font-weight: 500;
            border-radius: 4px;
            transition: background-color 0.3s;
            width: 100%;
        }

        .register-btn:hover {
            background-color: #c13434;
            color: white;
        }

        .login-link {
            color: #000;
            text-decoration: none;
            font-weight: 500;
            border-bottom: 1px solid #000;
            transition: opacity 0.3s;
        }

        .login-link:hover {
            opacity: 0.7;
            color: #000;
        }

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
                <img src="{{ asset('images/login.jpeg') }}"
                    alt="Exclusive Shopping Banner"
                    class="img-fluid"
                    onerror="this.onerror=null;this.style.display='none';"
                    style="height: 100%; width: 100%; object-fit: contain; background:white;">
            </div>

            <!-- Right Side: Register Form -->
            <div class="col-lg-6 auth-form-side">
                <h2 class="mb-2 fw-bold">Create an account</h2>
                <p class="mb-5 text-muted">Enter your details below</p>

                <!-- IMPORTANT: Aapko apne RegisterController mein bhi 'date_of_birth' ko handle karna padega -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="mb-3">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            placeholder="Name">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Email or Phone Field -->
                    <div class="mb-3">
                        <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email"
                            placeholder="Email or Phone Number">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- NEW: Date of Birth Field -->
                    <div class="mb-3">
                        <!-- Note: Type 'date' will show a calendar picker -->
                        <label for="date_of_birth" class="text-muted" style="font-size: 0.9rem; margin-bottom: 5px; display: block;">Date of Birth (MM/DD/YYYY)</label>
                        <input id="date_of_birth" type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                            name="date_of_birth" value="{{ old('date_of_birth') }}" required
                            style="border-top: none; padding-left: 5px;">
                        @error('date_of_birth')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="new-password"
                            placeholder="Password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-3">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                    </div>
                    <div class="form-group row">
                        <label for="captcha" class="col-md-4 col-form-label text-md-right">
                            Verification Question
                        </label>

                        <div class="col-md-6">
                            {{-- Question Session se dikhao --}}
                            <p>Please solve: <strong>{{ Session::get('captcha_q') }}</strong></p>

                            <input id="captcha" type="text"
                                class="form-control @error('captcha_answer') is-invalid @enderror"
                                name="captcha_answer" required autocomplete="off">

                            @error('captcha_answer')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <!-- Create Account Button -->
                    <button type="submit" class="btn register-btn mt-4 mb-4">
                        {{ __('Create Account') }}
                    </button>

                    <!-- Already have an account? Login link -->
                    <div class="text-center mt-3">
                        <span class="text-muted">Already have account?</span>
                        <a class="login-link ms-2" href="{{ route('login') }}">Log in</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection