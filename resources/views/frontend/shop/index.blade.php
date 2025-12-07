@extends('frontend.layouts.app')

@section('title', 'Shop - Products')
@push('styles')
<link href="{{ asset('css/frontend/shop.css') }}" rel="stylesheet">
@endpush

@section('content')
{{-- Wishlist Product IDs ko JavaScript ke liye JSON encode karo --}}
@php
$wishlistProductIds = $wishlistProductIds ?? [];
$minPrice = $minPrice ?? '';
$maxPrice = $maxPrice ?? '';
@endphp

{{-- Global data hooks for JavaScript to access Laravel variables --}}
{{-- Yeh elements JavaScript ko CSRF token, Auth status, aur route URL provide karte hain --}}
<div id="auth-status" data-is-auth="{!! Auth::check() ? 'true' : 'false' !!}" style="display: none;"></div>
<div id="csrf-token-data" data-token="{!! csrf_token() !!}" style="display: none;"></div>



<div class="container my-5">

    {{-- Top Section: Heading and Breadcrumb --}}
    <div class="row mb-5">
        <div class="col-12">
            <p class="breadcrumb-text">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a> / Shop
            </p>
            <h1 class="shop-heading">SHOP WITH US.</h1>
            <p class="text-muted">
                Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} results
            </p>
        </div>
    </div>

    <div class="row">

        {{-- 1. LEFT SIDEBAR: FILTERS --}}
        <div class="col-lg-3 col-md-4 filter-sidebar">
            <form method="GET" action="{{ route('shop.index') }}" class="border p-3 rounded shadow-sm mb-4 bg-white">
                <h5 class="mb-3 text-uppercase fw-bold">Filter Products</h5>

                <div class="mb-3">
                    <label for="category" class="form-label fw-semibold">Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="all" {{ empty($currentCategory) || $currentCategory == 'all' ? 'selected' : '' }}>All Categories</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $currentCategory == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Price Range ($)</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number"
                                name="min_price"
                                class="form-control"
                                placeholder="Min"
                                value="{{ $minPrice ?? $priceRange['min'] }}"
                                min="{{ $priceRange['min'] }}"
                                max="{{ $priceRange['max'] }}">
                        </div>
                        <div class="col-6">
                            <input type="number"
                                name="max_price"
                                class="form-control"
                                placeholder="Max"
                                value="{{ $maxPrice ?? $priceRange['max'] }}"
                                min="{{ $priceRange['min'] }}"
                                max="{{ $priceRange['max'] }}">
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-funnel-fill me-1"></i> Apply Filter
                    </button>

                    @if(request()->has('category') || request()->has('min_price') || request()->has('max_price'))
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
                    </a>
                    @endif
                </div>
            </form>

        </div>

        {{-- 2. RIGHT CONTENT: PRODUCT GRID --}}
        <div class="col-lg-9 col-md-8">
            <div class="row g-4">
                @forelse($products as $product)
                @php
                $isSlugValid = !empty($product->slug);
                $productLink = $isSlugValid ? route('shop.show', ['slug' => $product->slug]) : route('shop.index');
                $placeholderUrl = 'https://via.placeholder.com/300x300/E0E0E0/333333?text=No+Image';
                $imageSource = (!empty($product->image))
                ? asset('storage/' . $product->image)
                : $placeholderUrl;

                // Check if current product is in the wishlist
                $isWishlisted = in_array($product->id, $wishlistProductIds);

                // Route URL template with product ID embedded
                $wishlistRouteUrl = route('wishlist.toggle', ['product' => $product->id]);

                // ✅ Stock Status Check
                $statusKey = $product->stock_status ?? \App\Models\Product::STATUS_IN_STOCK;

                $isAvailable = $statusKey !== \App\Models\Product::STATUS_NOT_AVAILABLE;

                if ($statusKey === \App\Models\Product::STATUS_IN_STOCK) {
                $statusBadge = 'bg-success';
                $statusText = 'In Stock';
                } elseif ($statusKey === \App\Models\Product::STATUS_LOW_STOCK) {
                $statusBadge = 'bg-warning text-dark'; // Low Stock ke liye yellow badge
                $statusText = 'Low Stock';
                } else {
                $statusBadge = 'bg-danger';
                $statusText = 'Not Available';
                }
                @endphp

                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="product-card shadow-sm">
                        <div class="product-img position-relative overflow-hidden">
                            {{-- Status Badge Display (Optional, but useful) --}}
                            <span class="badge {{ $statusBadge }} position-absolute top-0 start-0 m-2 z-index-1">
                                {{ $statusText }}
                            </span>
                            <img src="{{ $imageSource }}"
                                onerror="this.onerror=null;this.src='{{ $placeholderUrl }}';"
                                class="img-fluid w-100"
                                alt="{{ $product->name }}">

                            {{-- WISHLIST ICON: ✅ FIX: @onclick ko 'onclick' se replace kiya aur sirf 'this' pass kiya --}}
                            <div class="wishlist-icon"
                                data-toggle-route="{{ route('wishlist.toggle', ['product' => $product->id]) }}"
                                onclick="toggleWishlist(this)">
                                <i class="wishlist-icon__heart {{ $isWishlisted ? 'fas is-wishlisted' : 'far' }} fa-heart"></i>
                            </div>
                            @php
                            // ... (Placeholder URL aur baaki code) ...

                            // Check if slug is valid
                            $isSlugValid = !empty($product->slug);

                            // ✅ FIX: Agar Slug valid nahi hai, toh Product ID use karke details page ka link banao
                            $productLink = $isSlugValid
                            ? route('shop.show', ['slug' => $product->slug])
                            : (isset($product->id) ? route('shop.show', ['slug' => $product->id]) : route('shop.index')); // Fallback to index if no ID/Slug

                            // Agar aapka shop.show route sirf slug leta hai, toh aapko ID ko slug ki jagah use karna hoga.
                            // Assuming your shop.show route can handle either slug or ID being passed as 'slug':
                            $productLink = route('shop.show', ['slug' => $product->slug ?? $product->id]);
                            @endphp

                            <div class="product-overlay d-flex flex-column justify-content-center align-items-center">
                                <a href="{{ $productLink }}"
                                    class="btn btn-light mb-2 text-uppercase fw-semibold">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </a>
                                {{-- ✅ ADD TO CART BUTTON CHECK --}}
                                {{-- ✅ ADD TO CART BUTTON LOGIC --}}
                                @if ($isAvailable)
                                {{-- Button enabled for In Stock and Low Stock --}}
                                <button class="btn btn-danger text-uppercase fw-semibold" onclick="addToCart(this)" data-route="{{ route('cart.add', ['product' => $product->id]) }}">
                                    <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                </button>
                                @else
                                {{-- Button disabled for Not Available --}}
                                <button class="btn btn-secondary text-uppercase fw-semibold" disabled>
                                    Not Available
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="product-info text-center p-3">
                            <h6 class="product-name fw-semibold text-dark mb-2">{{ $product->name }}</h6>
                            <div class="star-rating mb-1">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i><i class="far fa-star"></i>
                            </div>
                            <p class="product-price mb-0 text-danger fw-bold">${{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>
                </div>

                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        No products found in this selection.
                    </div>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>

    </div>
</div>


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
<script src="{{ asset('js/shop.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.querySelector('select[name="category"]');
        select.addEventListener('change', () => {
            if (select.value === '') {
                const url = new URL(window.location.href);
                url.searchParams.delete('category');
                window.location.href = url.toString();
            } else {
                select.form.submit();
            }
        });
    });
</script>
@endpush