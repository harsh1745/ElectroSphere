@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

<!-- ==================================================================== -->
<!-- 1. HERO SECTION (STATIC BANNER) -->
<!-- ==================================================================== -->
<section id="hero-section" class="hero-section">
    <div class="container container-xl p-0">
        <div class="row align-items-center hero-static-content">

            <!-- Text Content -->
            <div class="col-md-6 col-lg-5 p-4 p-md-5">
                <span class="badge text-uppercase fw-bold rounded-pill mb-3 py-2 px-3 glass-badge-red">
                    New Launch
                </span>

                <h1 class="fw-bolder display-4 mb-3 hero-title">
                    Apple Watch Ultra 2
                </h1>

                <p class="lead mb-4 hero-subtitle">
                    Rugged, capable, and built to meet the demands of endurance athletes, outdoor adventurers, and water sports enthusiasts.
                </p>

                <a href="#" class="btn btn-lg px-5 py-3 fw-bold rounded-pill shadow-lg glass-btn-red">
                    Discover More
                </a>
            </div>

            <!-- Image -->
            <div class="col-md-6 col-lg-7 text-center hero-image-col">
                <img src="{{ asset('images/Iphone-Image.png') }}"
                    onerror="this.onerror=null; this.src='https://placehold.co/800x600/f8f9fa/333333?text=Hero+Product+Image';"
                    alt="Apple Watch Ultra 2"
                    class="img-fluid hero-img">
            </div>

        </div>
    </div>
</section>


<!-- ==================================================================== -->
<!-- 2. CATEGORIES SECTION (Updated as per new image) -->
<!-- ==================================================================== -->
<section id="categories-section" class="py-5 bg-white">
    <div class="container container-xl">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div class="d-flex flex-column gap-4">
                <p class="text-uppercase mb-2 fw-bold categories-label ">Categories</p>
                <h2 class="fw-bold categories-heading">Browse By Category</h2>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="row g-4 justify-content-start">

            <!-- Category Card: Phones -->
            <div class="col-lg-2 col-md-4 col-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card text-center h-100 p-4 category-card-outline">
                        <!-- Image placeholder for Phones -->
                        <img src="images/Category-CellPhone.svg" alt="Phones" class="img-fluid mx-auto category-img mb-3">
                        <p class="fw-semibold mb-0 mt-3">Phones</p>
                    </div>
                </a>
            </div>

            <!-- Category Card: Computers -->
            <div class="col-lg-2 col-md-4 col-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card text-center h-100 p-4 category-card-outline">
                        <!-- Image placeholder for Computers -->
                        <img src="images/Category-Computer.svg" alt="Computers" class="img-fluid mx-auto category-img mb-3">
                        <p class="fw-semibold mb-0 mt-3">Computers</p>
                    </div>
                </a>
            </div>

            <!-- Category Card: SmartWatch -->
            <div class="col-lg-2 col-md-4 col-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card text-center h-100 p-4 category-card-outline">
                        <!-- Image placeholder for SmartWatch -->
                        <img src="images/Category-SmartWatch.svg" alt="SmartWatch" class="img-fluid mx-auto category-img mb-3">
                        <p class="fw-semibold mb-0 mt-3">SmartWatch</p>
                    </div>
                </a>
            </div>

            <!-- Category Card: Camera -->
            <div class="col-lg-2 col-md-4 col-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card text-center h-100 p-4 category-card-outline">
                        <!-- Image placeholder for Camera -->
                        <img src="images/Category-Camera.svg" alt="Camera" class="img-fluid mx-auto category-img mb-3">
                        <p class="fw-semibold mb-0 mt-3">Camera</p>
                    </div>
                </a>
            </div>

            <!-- Category Card: Headphones -->
            <div class="col-lg-2 col-md-4 col-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card text-center h-100 p-4 category-card-outline">
                        <!-- Image placeholder for Headphones -->
                        <img src="images/Category-Headphone.svg" alt="HeadPhones" class="img-fluid mx-auto category-img mb-3">
                        <p class="fw-semibold mb-0 mt-3">HeadPhones</p>
                    </div>
                </a>
            </div>

            <!-- Category Card: Gaming -->
            <div class="col-lg-2 col-md-4 col-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="card text-center h-100 p-4 category-card-outline">
                        <!-- Image placeholder for Gaming -->
                        <img src="images/Category-Gamepad.svg" alt="Gaming" class="img-fluid mx-auto category-img mb-3">
                        <p class="fw-semibold mb-0 mt-3">Gaming</p>
                    </div>
                </a>
            </div>

        </div>
    </div>
</section>

<!-- ==================================================================== -->
<!-- FEATURED ICONS SECTION (Image se match karta hua) -->
<!-- ==================================================================== -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4 gap-3 " style="padding: 30px;">
                <h1 class="fa fa-check light-red  m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4 gap-3 " style="padding: 30px;">
                <h1 class="fa fa-shipping-fast light-red m-0 mr-2"></h1>
                <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4 gap-3 " style="padding: 30px;">
                <h1 class="fas fa-exchange-alt light-red  m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4 gap-3 " style="padding: 30px;">
                <h1 class="fa fa-phone-volume light-red  m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
            </div>
        </div>
    </div>
</div>
<!-- Featured End -->

@endsection

@push('styles')
<!-- Page Specific CSS (Linked from public/css/home.css) -->
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<!-- No custom JavaScript needed for static layout -->
@endpush