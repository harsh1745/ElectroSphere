@extends('frontend.layouts.app')

@section('title', 'Shop - Products')

@section('content')

<style>
    /* Custom Styles for Shop Page UI */
    .shop-heading {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .breadcrumb-text {
        font-size: 0.85rem;
        color: #808080;
    }

    .product-card {
        border: none;
        border-radius: 0;
        text-align: center;
        transition: box-shadow 0.3s;
    }

    .product-card:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .product-card-body {
        padding: 1rem 0;
    }

    .product-image-wrapper {
        background-color: #f5f5f5;
        /* Light grey background jaisa image mein hai */
        position: relative;
        padding: 10px;
        overflow: hidden;
    }

    .product-image-wrapper img {
        height: 250px;
        object-fit: contain;
        width: 100%;
        transition: opacity 0.3s;
    }

    /* Product Hover Overlay CSS */
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .product-image-wrapper:hover .product-overlay {
        opacity: 1;
    }

    .product-image-wrapper:hover img {
        opacity: 0.8;
    }

    .wishlist-icon {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
        color: #333;
        background-color: white;
        padding: 8px;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        cursor: pointer;
        transition: color 0.3s;
    }

    .wishlist-icon:hover {
        color: #DB4444;
        /* Red hover effect */
    }

    .product-card .card-title {
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }

    .product-card .card-price {
        color: #DB4444;
        /* Red accent for price */
        font-weight: 700;
        font-size: 1.1rem;
    }

    .star-rating i {
        color: #FFAD33;
        /* Gold/Orange for stars */
        font-size: 0.9rem;
    }

    /* Filters Sidebar */
    .filter-section {
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0e0e0;
    }

    .filter-section:last-child {
        border-bottom: none;
    }

    .filter-section h5 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .filter-link {
        color: #333;
        text-decoration: none;
        display: block;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .filter-link:hover,
    .filter-link.active {
        color: #DB4444;
        font-weight: 600;
    }

    /* Banners Section */
    .banners-section img {
        height: 350px;
        object-fit: cover;
        width: 100%;
    }

    /* Features Section */
    .features-section {
        background-color: #FAFAFA;
        padding: 50px 0;
    }

    .feature-item {
        text-align: center;
    }

    .feature-icon {
        background-color: #212121;
        color: white;
        border-radius: 50%;
        padding: 15px;
        font-size: 1.5rem;
        display: inline-block;
        margin-bottom: 15px;
        line-height: 1;
    }
</style>

<div class="container my-5">

    {{-- Top Section: Heading and Breadcrumb --}}
    <div class="row mb-5">
        <div class="col-12">
            <p class="breadcrumb-text">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a> / Shop
            </p>
            <h1 class="shop-heading">SHOP WITH US</h1>
            <p class="text-muted">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results
            </p>
        </div>
    </div>

    <div class="row">

        {{-- 1. LEFT SIDEBAR: FILTERS --}}
        <div class="col-lg-3 col-md-4">

            <form action="{{ route('shop.index') }}" method="GET">
                {{-- Hidden input to maintain search/other filters on category change --}}
                @foreach(request()->except(['category', 'page']) as $key => $value)
                @if(!empty($value))
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
                @endforeach

                {{-- Category Filter (Image mein dropdown jaisa hai) --}}
                <div class="filter-section">
                    <h5>Categories</h5>
                    <select name="category" onchange="this.form.submit()" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->slug }}"
                            {{ request('category') == $category->slug ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Price Filter (Example Filter) --}}
                <div class="filter-section">
                    <h5>Price Range</h5>
                    <div class="mb-2">
                        <input type="number" name="min_price" placeholder="Min Price" class="form-control form-control-sm" value="{{ request('min_price') }}">
                    </div>
                    <div class="mb-2">
                        <input type="number" name="max_price" placeholder="Max Price" class="form-control form-control-sm" value="{{ request('max_price') }}">
                    </div>
                    <button type="submit" class="btn btn-sm btn-dark w-100">Apply Filter</button>
                </div>

                <!-- {{-- Type Filter (Static Example) --}}
                <div class="filter-section">
                    <h5>Type</h5>
                    <a href="#" class="filter-link active">Knitted Jumper</a>
                    <a href="#" class="filter-link">Cotton Shirt</a>
                    <a href="#" class="filter-link">Denim Jeans</a>
                </div> -->

            </form>
        </div>

        {{-- 2. RIGHT CONTENT: PRODUCT GRID --}}
        <div class="col-lg-9 col-md-8">
            <div class="row">

                @forelse($products as $product)
                @php
                $isSlugValid = !empty($product->slug);
                $productLink = $isSlugValid ? route('shop.show', ['slug' => $product->slug]) : route('shop.index');
                $placeholderUrl = 'https://via.placeholder.com/250x250?text=No+Image';

                // ✅ FIXED IMAGE URL (storage/products/filename.jpg)
                $imageSource = (!empty($product->image))
                ? asset('storage/' . $product->image)
                : $placeholderUrl;
                @endphp


                <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                    <div class="card product-card h-100">
                        <a href="{{ $productLink }}" style="text-decoration: none; color: inherit;">
                            <div class="product-image-wrapper">
                                <img src="{{ $imageSource }}"
                                    onerror="this.onerror=null;this.src='{{ $placeholderUrl }}';"
                                    class="img-fluid"
                                    alt="{{ $product->name }}">
                                <i class="far fa-heart wishlist-icon"></i>

                                <div class="product-overlay">
                                    @if ($isSlugValid)
                                    <button class="btn btn-danger btn-sm text-uppercase fw-bold"
                                        onclick="window.location.href='{{ $productLink }}'; return false;">
                                        View Product
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </a>
                        <div class="card-body product-card-body">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <div class="star-rating mb-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i>
                            </div>
                            <p class="card-price">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                </div>

                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center" role="alert">
                        No products found in this selection.
                    </div>
                </div>
                @endforelse

            </div>

            {{-- Pagination Links --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

{{-- 3. BOTTOM BANNERS SECTION --}}
<!-- <div class="container my-5 banners-section">
    <div class="row g-4">
        <div class="col-lg-3 col-md-6">
            <img src="https://via.placeholder.com/280x350/E0E0E0/333333?text=Promotion+Banner+1" class="img-fluid rounded" alt="Promotion Banner 1">
        </div>
        <div class="col-lg-3 col-md-6">
            <img src="https://via.placeholder.com/280x350/E0E0E0/333333?text=Promotion+Banner+2" class="img-fluid rounded" alt="Promotion Banner 2">
        </div>
        <div class="col-lg-3 col-md-6">
            <img src="https://via.placeholder.com/280x350/E0E0E0/333333?text=Promotion+Banner+3" class="img-fluid rounded" alt="Promotion Banner 3">
        </div>
        <div class="col-lg-3 col-md-6">
            <img src="https://via.placeholder.com/280x350/E0E0E0/333333?text=Promotion+Banner+4" class="img-fluid rounded" alt="Promotion+Banner+4">
        </div>
    </div>
</div> -->

{{-- 4. FEATURES SECTION --}}
<div class="features-section">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-lg-3 col-md-6 feature-item">
                <div class="feature-icon"><i class="fas fa-truck"></i></div>
                <h5>Fast & Free Delivery</h5>
                <p class="text-muted small">Free delivery for all orders over $140</p>
            </div>
            <div class="col-lg-3 col-md-6 feature-item">
                <div class="feature-icon"><i class="fas fa-headphones-alt"></i></div>
                <h5>24/7 Customer Support</h5>
                <p class="text-muted small">Friendly 24/7 customer support</p>
            </div>
            <div class="col-lg-3 col-md-6 feature-item">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h5>Money Back Guarantee</h5>
                <p class="text-muted small">We return money within 30 days</p>
            </div>
            <div class="col-lg-3 col-md-6 feature-item">
                <div class="feature-icon"><i class="fas fa-wallet"></i></div>
                <h5>Secure Payment</h5>
                <p class="text-muted small">100% Secure payment guarantee</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Agar koi JS zaroori ho toh yahan add karein --}}
@endpush