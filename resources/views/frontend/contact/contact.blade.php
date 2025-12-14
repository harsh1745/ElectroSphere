@extends('frontend.layouts.app')

@section('title', 'Contact Us')

@section('content')
<div id="auth-status" data-is-auth="{!! Auth::check() ? 'true' : 'false' !!}" style="display: none;"></div>

<section class="py-5" style="background:#f7f7f7;">
    <div class="container container-xl">

        <div class="text-center mb-5">
            <h2 class="fw-bold contact-heading">Contact Us</h2>
            <p class="text-muted">Any question or remarks? Just write us a message!</p>
        </div>

        <div class="row">

            <!-- LEFT INFO CARD -->
            <div class="col-lg-5 mb-4">
                <div class="p-4 rounded-4 shadow contact-left-card">

                    <h4 class="text-white">Contact Information</h4>
                    <p class="text-white-50">Say something to start a live chat!</p>

                    <div class="mt-4 text-white">
                        <p><i class="fa fa-phone me-2"></i> +91 1012 3456 789</p>
                        <p><i class="fa fa-envelope me-2"></i> hello@yourdomain.com</p>
                        <p><i class="fa fa-map-marker me-2"></i>
                            132 Dartmouth Street Boston, MA 02156, United States
                        </p>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="social-link"><i class="fab fa-twitter social-icon"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-facebook social-icon"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram social-icon"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin social-icon"></i></a>
                    </div>

                </div>
            </div>

            <!-- RIGHT FORM CARD -->
            <div class="col-lg-7">
                <div class="p-5 bg-white shadow rounded-4">

                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">First Name</label>
                                <input name="first_name" value="{{ old('first_name') }}" class="form-control @error('first_name') is-invalid @enderror" />
                                @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Last Name</label>
                                <input name="last_name" value="{{ old('last_name') }}" class="form-control @error('last_name') is-invalid @enderror" />
                                @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Email</label>
                                <input name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" />
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col">
                                <label class="form-label">Phone Number</label>
                                <input name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" />
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <label class="mb-2 d-block">Select Subject:</label>
                        <div class="d-flex gap-4 mb-3 align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subject" id="sub1" value="General Inquiry" {{ old('subject','General Inquiry') == 'General Inquiry' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sub1">General Inquiry</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subject" id="sub2" value="Support" {{ old('subject') == 'Support' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sub2">Support</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="subject" id="sub3" value="Feedback" {{ old('subject') == 'Feedback' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sub3">Feedback</label>
                            </div>
                        </div>
                        @error('subject') <div class="text-danger mb-3">{{ $message }}</div> @enderror

                        <label class="form-label">Message</label>
                        <textarea name="message" rows="5" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                        @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror

                        <div class="mt-4">
                            <button type="submit" class="theme-btn">
                                Send Message
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
    /* THEME PRIMARY */
    :root {
        --theme-red: #DB4444;
        --theme-red-dark: #b83838;
    }

    .contact-heading {
        color: var(--theme-red);
    }

    /* LEFT INFO CARD */
    .contact-left-card {
        background: linear-gradient(180deg, #071226 0%, #0D1B2A 100%);
        border-radius: 20px;
        padding: 36px;
        height: 100%;
        position: relative;
        overflow: hidden;
        color: #fff;
    }

    .contact-left-card::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        background: rgba(219, 68, 68, 0.14);
        border-radius: 50%;
        bottom: -40px;
        right: -40px;
        filter: blur(12px);
        pointer-events: none;
    }

    .contact-left-card h4 {
        font-weight: 700;
    }

    .contact-left-card p {
        margin-bottom: 0.6rem;
    }

    /* SOCIAL ICONS */
    .social-icon {
        font-size: 20px;
        color: #fff;
        transition: transform .18s ease, color .18s ease;
    }

    .social-link {
        text-decoration: none;
    }

    .social-icon:hover {
        color: var(--theme-red);
        transform: scale(1.12);
    }

    /* FORM INPUTS */
    .form-control {
        border: none;
        border-bottom: 2px solid #e9e9e9;
        border-radius: 0;
        padding: 10px 8px;
        box-shadow: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .form-control:focus {
        border-bottom-color: var(--theme-red);
        box-shadow: 0 4px 18px rgba(219, 68, 68, 0.06);
        outline: none;
    }

    /* RADIO ACCENT (modern browsers) */
    input[type="radio"]:checked {
        accent-color: var(--theme-red);
    }

    /* THEME BUTTON */
    .theme-btn {
        background: var(--theme-red);
        border: 1px solid var(--theme-red);
        color: #fff;
        padding: 10px 36px;
        border-radius: 50px;
        font-weight: 600;
        transition: background .15s ease, transform .12s ease, box-shadow .12s ease;
    }

    .theme-btn:hover {
        background: var(--theme-red-dark);
        border-color: var(--theme-red-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(219, 68, 68, 0.12);
    }

    /* Validation styles (Bootstrap compatible) */
    .is-invalid {
        border-bottom-color: #dc3545 !important;
    }

    .invalid-feedback {
        display: block;
    }

    /* Responsive tweaks */
    @media (max-width: 767px) {
        .contact-left-card {
            padding: 24px;
            border-radius: 12px;
        }
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('js/sweetalert.js') }}"></script>

@if(session('success'))
<script>
    Swal.fire({
        title: "Success!",
        text: "{{ session('success') }}",
        icon: "success",
        confirmButtonColor: "#DB4444",
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif
<script src="{{ asset('js/shop.js') }}"></script>


@endpush