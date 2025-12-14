@extends('frontend.layouts.app')

@section('title', 'Home')
@push('styles')
<link href="{{ asset('css/frontend/shop.css') }}" rel="stylesheet">
@endpush

@php
use App\Models\Product;
use Illuminate\Support\Facades\DB;

// 🔥 TOP ORDERED PRODUCTS USING SUBQUERY (STRICT MODE SAFE)
$topProducts = Product::select('products.*')
->addSelect(DB::raw('(SELECT COUNT(*) FROM order_details WHERE order_details.product_id = products.id) AS total_sold'))
->orderByDesc('total_sold')
->take(8)
->get();

// If no orders exist → show random products
if ($topProducts->isEmpty()) {
$topProducts = Product::inRandomOrder()->take(8)->get();
}

// Other product sections
$trendingProducts = Product::inRandomOrder()->take(6)->get();
$latestArrivals = Product::latest()->take(6)->get();
$recommendedProducts = Product::inRandomOrder()->take(6)->get();
@endphp

{{-- Required for JS --}}
<div id="auth-status" data-is-auth="{{ Auth::check() ? 'true' : 'false' }}" style="display:none;"></div>
<div id="csrf-token-data" data-token="{{ csrf_token() }}" style="display:none;"></div>


@section('content')

<!-- ==================================================================== -->
<!-- 1. HERO BANNER -->
<!-- ==================================================================== -->
<section id="hero-section" class="hero-section">
    <div class="container container-xl p-0">
        <div class="row align-items-center hero-static-content">
            <div class="col-md-6 col-lg-5 p-4 p-md-5">
                <span class="badge glass-badge-red mb-3 py-2 px-3 fw-bold">New Launch</span>
                <h1 class="fw-bolder display-4 hero-title">Apple Watch Ultra 2</h1>
                <p class="lead hero-subtitle mb-4">
                    Rugged, powerful and designed for adventurers and athletes.
                </p>
                <a href="#" class="btn btn-lg px-5 py-3 fw-bold rounded-pill shadow-lg glass-btn-red">
                    Discover More
                </a>
            </div>

            <div class="col-md-6 col-lg-7 text-center hero-image-col">
                <img src="{{ asset('images/Iphone-Image.png') }}"
                    class="img-fluid hero-img"
                    onerror="this.src='https://placehold.co/800x600/f0f0f0/333?text=Product+Image';">
            </div>
        </div>
    </div>
</section>


<!-- ==================================================================== -->
<!-- 2. CATEGORIES -->
<!-- ==================================================================== -->
<section class="py-5 bg-white">
    <div class="container container-xl">
        <p class="text-uppercase fw-bold mb-2 categories-label">Categories</p>
        <h2 class="fw-bold mb-4">Browse By Category</h2>

        <div class="row g-4">
            @php
            $cats = [
            ['Phones', 'Category-CellPhone.svg'],
            ['Computers', 'Category-Computer.svg'],
            ['SmartWatch', 'Category-SmartWatch.svg'],
            ['Camera', 'Category-Camera.svg'],
            ['HeadPhones', 'Category-Headphone.svg'],
            ['Gaming', 'Category-Gamepad.svg']
            ];
            @endphp

            @foreach ($cats as $cat)
            <div class="col-lg-2 col-md-4 col-6">
                <div class="card text-center h-100 p-4 category-card-outline">
                    <img src="{{ asset('images/' . $cat[1]) }}" class="img-fluid mb-3 category-img">
                    <p class="fw-semibold mb-0">{{ $cat[0] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- ==================================================================== -->
<!-- 3. FEATURES -->
<!-- ==================================================================== -->
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">

        @php
        $features = [
        ['fa-check', 'Quality Product'],
        ['fa-shipping-fast', 'Free Shipping'],
        ['fa-exchange-alt', '14-Day Return'],
        ['fa-phone-volume', '24/7 Support'],
        ];
        @endphp

        @foreach ($features as $f)
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4 gap-3" style="padding: 30px;">
                <h1 class="fa {{ $f[0] }} light-red"></h1>
                <h5>{{ $f[1] }}</h5>
            </div>
        </div>
        @endforeach

    </div>
</div>

<!-- ==================================================================== -->
<!-- 4. TOP SELLING PRODUCTS (SHOP CARD UI) -->
<!-- ==================================================================== -->
<section class="py-5 bg-light">
    <div class="container container-xl">

        <div class="d-flex justify-content-between mb-4">
            <h2 class="fw-bold">Most Ordered Products</h2>
            <a class="text-danger fw-bold" href="{{ route('shop.index') }}">View All →</a>
        </div>

        <div class="row g-4">
            @foreach($topProducts as $product)
            @include('frontend.Home.partials.product-card', ['product' => $product])
            @endforeach
        </div>

    </div>
</section>


<!-- ==================================================================== -->
<!-- 5. TRENDING -->
<!-- ==================================================================== -->
<section class="py-5 bg-white">
    <div class="container container-xl">
        <h2 class="fw-bold mb-4">Trending Products</h2>

        <div class="row g-4">
            @foreach($trendingProducts as $product)
            @include('frontend.Home.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>


<!-- ==================================================================== -->
<!-- 6. LATEST ARRIVALS -->
<!-- ==================================================================== -->
<section class="py-5 bg-light">
    <div class="container container-xl">
        <h2 class="fw-bold mb-4">Latest Arrivals</h2>

        <div class="row g-4">
            @foreach($latestArrivals as $product)
            @include('frontend.Home.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>


<!-- ==================================================================== -->
<!-- 7. RECOMMENDED -->
<!-- ==================================================================== -->
<section class="py-5 bg-white">
    <div class="container container-xl">
        <h2 class="fw-bold mb-4">Recommended For You</h2>

        <div class="row g-4">
            @foreach($recommendedProducts as $product)
            @include('frontend.Home.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>


<!-- ==================================================================== -->
<!-- 8. DEAL OF THE DAY -->
<!-- ==================================================================== -->
<section class="py-5 bg-white deal-section">
    <div class="container container-xl">

        <div class="row align-items-center">

            <!-- LEFT CONTENT -->
            <div class="col-lg-6 mb-4 mb-lg-0">

                <span class="badge bg-danger px-3 py-2 fw-bold rounded-pill mb-3">
                    Limited Time Offer
                </span>

                <h2 class="fw-bold display-5 mb-3">🔥 Deal of The Day</h2>

                <p class="lead text-muted mb-4">
                    Grab the best deals on top gadgets before the timer runs out!
                </p>

                <!-- Modern Countdown -->
                <div id="countdown" class="d-flex gap-4 my-4">

                    <div class="text-center px-3 py-2 rounded countdown-box">
                        <h3 id="days" class="fw-bold mb-0 countdown-number"></h3>
                        <small class="text-muted">Days</small>
                    </div>

                    <div class="text-center px-3 py-2 rounded countdown-box">
                        <h3 id="hours" class="fw-bold mb-0 countdown-number"></h3>
                        <small class="text-muted">Hours</small>
                    </div>

                    <div class="text-center px-3 py-2 rounded countdown-box">
                        <h3 id="minutes" class="fw-bold mb-0 countdown-number"></h3>
                        <small class="text-muted">Minutes</small>
                    </div>

                    <div class="text-center px-3 py-2 rounded countdown-box">
                        <h3 id="seconds" class="fw-bold mb-0 countdown-number"></h3>
                        <small class="text-muted">Seconds</small>
                    </div>

                </div>

                <a href="{{ route('shop.index') }}"
                    class="btn btn-danger btn-lg rounded-pill px-5 shadow mt-3 fw-bold">
                    Shop Now →
                </a>

            </div>

            <!-- RIGHT IMAGE -->
            <div class="col-lg-6 text-center">
                <img src="{{ asset('images/deal-of-the-day.jpg') }}"
                    alt="Deal of the Day"
                    class="img-fluid deal-img" width="300">
            </div>

        </div>

    </div>
</section>

<!-- ========================================================= -->
<!-- FLASH SALE (IMPROVED UI) -->
<!-- ========================================================= -->
<section class="py-5 flash-sale-section">
    <div class="container container-xl">

        <div class="section-header d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">🔥 Flash Sale</h2>
            <span id="flash-timer" class="flash-timer-text"></span>
        </div>

        <div class="flash-slider d-flex gap-4 overflow-auto pb-2">

            @foreach ($topProducts as $product)
            @php
            $placeholder = 'https://via.placeholder.com/300x300/E0E0E0/333?text=No+Image';
            $image = $product->image ? asset('storage/'.$product->image) : $placeholder;
            @endphp

            <div class="flash-card shadow-sm rounded-4">
                <div class="p-3">
                    <img src="{{ $image }}" class="flash-img">
                </div>

                <div class="p-3">
                    <h6 class="fw-semibold">{{ Str::limit($product->name, 28) }}</h6>

                    <p class="text-danger fw-bold mb-2">
                        ₹{{ number_format($product->price,2) }}
                    </p>

                    <a href="{{ route('shop.show',$product->slug) }}"
                        class="btn btn-danger btn-sm w-100 rounded-pill">
                        Buy Now
                    </a>
                </div>
            </div>
            @endforeach

        </div>

    </div>
</section>
<!-- ========================================================= -->
<!-- PROMO GRID (IMPROVED UI) -->
<!-- ========================================================= -->
<section class="py-5">
    <div class="container container-xl">
        <div class="row g-4">

            <div class="col-lg-4 col-md-6">
                <div class="promo clean-box red-bg rounded-4 p-4 text-white">
                    <h3 class="fw-bold">🎧 Headphones Sale</h3>
                    <p class="opacity-75">Up to 30% OFF on premium audio</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-light btn-sm rounded-pill">
                        Shop Now
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="promo clean-box dark-bg rounded-4 p-4 text-white">
                    <h3 class="fw-bold">⌚ Smart Watches</h3>
                    <p class="opacity-75">Starting from ₹1999</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-light btn-sm rounded-pill">
                        View Deals
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="promo clean-box grey-bg rounded-4 p-4 text-white">
                    <h3 class="fw-bold">🎮 Gaming Zone</h3>
                    <p class="opacity-75">Flat 20% OFF</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-light btn-sm rounded-pill">
                        Explore
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .red-bg {
        background: #db4444;
    }

    .dark-bg {
        background: #1a1a1a;
    }

    .grey-bg {
        background: #444;
    }

    .promo:hover {
        transform: translateY(-6px);
        transition: .3s ease;
        box-shadow: 0 10px 28px rgba(0, 0, 0, .12);
    }


    .testimonial {
        border: 1px solid #eee;
        transition: .3s ease;
    }

    .testimonial:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
    }

    .countdown-box {
        background: #f8f9fa;
        border: 1px solid #e6e6e6;
        min-width: 80px;
    }

    .countdown-number {
        font-size: 2rem;
        color: #d61c1c;
    }

    .flash-sale-section {
        background: #fff;
    }

    .flash-slider::-webkit-scrollbar {
        height: 6px;
    }

    .flash-slider::-webkit-scrollbar-thumb {
        background: #db4444;
        border-radius: 20px;
    }

    .flash-card {
        min-width: 230px;
        background: #fff;
        border: 1px solid #eee;
        transition: .3s ease;
    }

    .flash-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
    }

    .flash-img {
        width: 100%;
        height: 160px;
        object-fit: contain;
    }

    .flash-timer-text {
        font-size: 16px;
        font-weight: bold;
        color: #db4444;
    }
</style>

@endsection


@push('styles')
<link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/shop.js') }}"></script>

<script>
    // Countdown Timer
    let countdownDate = new Date().getTime() + (2 * 24 * 60 * 60 * 1000);

    setInterval(() => {
        let now = Date.now();
        let diff = countdownDate - now;

        document.getElementById("days").innerHTML = Math.floor(diff / (1000 * 60 * 60 * 24));
        document.getElementById("hours").innerHTML = Math.floor((diff / (1000 * 60 * 60)) % 24);
        document.getElementById("minutes").innerHTML = Math.floor((diff / 1000 / 60) % 60);
        document.getElementById("seconds").innerHTML = Math.floor((diff / 1000) % 60);
    }, 1000);
</script>
<script>
    // Flash Sale 6 Hours Timer
    let flashEnd = new Date().getTime() + (6 * 60 * 60 * 1000);

    setInterval(() => {
        let now = Date.now();
        let diff = flashEnd - now;

        let hrs = Math.floor((diff / (1000 * 60 * 60)));
        let mins = Math.floor((diff / 1000 / 60) % 60);
        let secs = Math.floor((diff / 1000) % 60);

        document.getElementById("flash-timer").innerHTML =
            `⏳ Ends in ${hrs}h ${mins}m ${secs}s`;
    }, 1000);
</script>

@endpush