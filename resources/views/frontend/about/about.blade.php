@extends('frontend.layouts.app')

@section('title', 'About Us')
@section('content')
<div id="auth-status" data-is-auth="{!! Auth::check() ? 'true' : 'false' !!}" style="display: none;"></div>

<!-- ================= HERO SECTION ================= -->
<section class="about-hero">
    <div class="container text-center">
        <h1 class="fw-bold">About <span style="color:#DB4444;">Our Store</span></h1>
        <p class="lead mt-3" style="color:white">
            We provide top-quality products with unbeatable customer experience.
        </p>
    </div>
</section>

<!-- ================= COMPANY SECTION ================= -->
<section class="py-5">
    <div class="container container-xl">
        <div class="row align-items-center">

            <div class="col-lg-6 mb-4">
                <img src="{{ asset('images/about-1.png') }}"
                    class="img-fluid" alt="About">
            </div>

            <div class="col-lg-6">
                <h2 class="fw-bold mb-3">Who We Are</h2>
                <p class="text-muted mb-4">
                    We are a modern eCommerce company dedicated to delivering high-quality tech
                    products at honest prices. Our mission is to make online shopping easy,
                    fast, and reliable for everyone.
                </p>

                <p class="text-muted mb-4">
                    With a strong focus on customer satisfaction, we ensure premium products,
                    secured payments, easy returns, and responsive support.
                </p>

                <ul class="about-list">
                    <li>✔ Premium Product Quality</li>
                    <li>✔ Fast & Reliable Shipping</li>
                    <li>✔ 24/7 Customer Support</li>
                    <li>✔ Hassle-free Returns</li>
                </ul>
            </div>

        </div>
    </div>
</section>

<!-- ================= STATS SECTION ================= -->
<section class="stats-section py-5">
    <div class="container text-center">

        <div class="row g-4">

            <div class="col-lg-3 col-6">
                <h1 class="fw-bold" style="color:#DB4444;">10K+</h1>
                <p class="text-muted">Happy Customers</p>
            </div>

            <div class="col-lg-3 col-6">
                <h1 class="fw-bold" style="color:#DB4444;">5K+</h1>
                <p class="text-muted">Orders Delivered</p>
            </div>

            <div class="col-lg-3 col-6">
                <h1 class="fw-bold" style="color:#DB4444;">150+</h1>
                <p class="text-muted">Premium Products</p>
            </div>

            <div class="col-lg-3 col-6">
                <h1 class="fw-bold" style="color:#DB4444;">4.9⭐</h1>
                <p class="text-muted">Customer Rating</p>
            </div>

        </div>

    </div>
</section>

<!-- ================= TEAM SECTION ================= -->
<section class="py-5">
    <div class="container container-xl text-center">

        <h2 class="fw-bold mb-4">Meet Our Team</h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="team-card shadow rounded-4 p-3">
                    <img src="{{ asset('images/profile-image-new.png') }}" class="team-img">
                    <h5 class="mt-3 fw-bold">Harsh Makwana</h5>
                    <p class="text-muted">Founder & CEO</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="team-card shadow rounded-4 p-3">
                    <img src="{{ asset('images/swayam.jpg') }}" class="team-img">
                    <h5 class="mt-3 fw-bold">Swayam Soni</h5>
                    <p class="text-muted">Tech Lead</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="team-card shadow rounded-4 p-3">
                    <img src="{{ asset('images/testimonial-2.png') }}" class="team-img">
                    <h5 class="mt-3 fw-bold">Emma Wilson</h5>
                    <p class="text-muted">Marketing Manager</p>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection

@push('styles')
<style>
    .about-hero {
        background: #111;
        padding: 80px 0;
        color: white;
        border-bottom-left-radius: 40px;
        border-bottom-right-radius: 40px;
    }

    .about-list li {
        list-style: none;
        padding: 5px 0;
        font-size: 16px;
        color: #444;
    }

    .about-list li::before {
        content: "✔ ";
        color: #DB4444;
        font-weight: bold;
    }

    .stats-section {
        background: #fafafa;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }

    .team-card {
        transition: .25s ease;
        background: white;
    }

    .team-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .team-img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('js/shop.js') }}"></script>
@endpush