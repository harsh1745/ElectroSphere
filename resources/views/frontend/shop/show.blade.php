@extends('frontend.layouts.app')

@section('title', $product->name)

@section('content')
{{-- Data hooks for JS (Required for Add to Cart) --}}
<div id="auth-status" data-is-auth="{!! Auth::check() ? 'true' : 'false' !!}" style="display: none;"></div>
<div id="csrf-token-data" data-token="{!! csrf_token() !!}" style="display: none;"></div>


<div class="container my-5">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}" class="text-decoration-none">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 20) }}</li>
        </ol>
    </nav>


    {{-- ==================================================================== --}}
    {{-- 1. TOP PRODUCT DETAIL SECTION (Image, Info, Actions) --}}
    {{-- ==================================================================== --}}
    @php
    // Hum assume kar rahe hain ki aapke Product Model mein yeh constants hain.
    // Agar nahi hain, toh values ko seedhe string se replace kar dein ('in_stock', 'low_stock', 'not_available')
    $IN_STOCK = 'in_stock';
    $LOW_STOCK = 'low_stock';
    $NOT_AVAILABLE = 'not_available';

    // Product ka actual status key fetch kiya
    $statusKey = $product->stock_status ?? $IN_STOCK;

    // ✅ FIX 1: $isAvailable sirf tab FALSE hoga jab status NOT_AVAILABLE ho.
    $isAvailable = ($statusKey !== $NOT_AVAILABLE);

    $disableControls = $isAvailable ? '' : 'disabled';

    // ✅ FIX 2: Status Text aur Class Set Karna
    if ($statusKey === $IN_STOCK) {
    $statusTextDisplay = 'In Stock';
    $statusClass = 'text-success';
    } elseif ($statusKey === $LOW_STOCK) {
    // 'Low Stock' dikhega aur button enabled rahega
    $statusTextDisplay = 'Low Stock - Order Soon';
    $statusClass = 'text-warning';
    } else {
    // Yeh sirf NOT_AVAILABLE ke liye chalega
    $statusTextDisplay = 'Currently Out of Stock';
    $statusClass = 'text-danger';
    }
    @endphp
    <div class="row mb-5 pb-5 border-bottom">

        {{-- Left Column: Image Gallery (Simplified) --}}
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="product-image-gallery">
                <img src="{{ asset('storage/' . $product->image) }}"
                    onerror="this.onerror=null;this.src='https://via.placeholder.com/600x600?text=Product+Image';"
                    alt="{{ $product->name }}"
                    class="img-fluid border rounded shadow-sm">
                {{-- Side thumbnails yahan aayengi --}}
            </div>
        </div>

        {{-- Right Column: Product Details and Actions --}}
        <div class="col-lg-6 product-details-actions">

            <h1 class="fw-bold mb-2">{{ $product->name }}</h1>
            <p class="sold-badge mb-3">
                {{ $soldCount }} Sold
            </p>


            <h2 class="text-danger fw-bolder mb-4">₹{{ number_format($product->price, 2) }}</h2>

            <p class="text-muted mb-4">{{ Str::limit($product->description, 200) }}</p>
            <p class="text-muted mb-4">
                <span class="fw-semibold {{ $statusClass }}">
                    {{ $statusTextDisplay }}
                </span>
                {{-- Agar aapko 'Out of Stock' badge sirf NOT_AVAILABLE par chahiye --}}
                @if ($statusKey === $NOT_AVAILABLE)
                <span class="badge bg-danger ms-2">Out of Stock</span>
                @endif
            </p>

            {{-- Quantity Selector (Disable if not available) --}}
            <div class="d-flex align-items-center mb-4">
                <span class="me-3 fw-semibold">Quantity:</span>
                <div class="input-group input-group-sm" style="width: 120px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantityInput(this, -1)" {{ $disableControls }}>-</button>

                    {{-- ✅ FIX 1: Max attribute ko $product->stock ya 1 set karein --}}
                    <input type="text"
                        name="quantity"
                        value="1"
                        min="1"
                        max="{{ $product->stock ?? 1 }}" {{-- Agar stock nahi hai, toh max 1 set karein --}}
                        class="form-control text-center"
                        {{ $disableControls }}>

                    <button class="btn btn-outline-secondary" type="button" onclick="changeQuantityInput(this, 1)" {{ $disableControls }}>+</button>
                </div>
            </div>


            {{-- Action Buttons --}}
            <div class="d-flex gap-3">
                {{-- Add to Cart Button --}}
                @if ($isAvailable)
                {{-- Add to Cart Button (Enabled for In Stock and Low Stock) --}}
                <button class="btn-add-cart"
                    {{ $disableControls }} {{-- Agar $isAvailable true hai, toh yeh empty rahega --}}
                    onclick="addToCart(this)"
                    data-route="{{ route('cart.add', ['product' => $product->id]) }}">
                    Add to Cart
                </button>

                {{-- Buy Now Button --}}
                <button class="btn-buy-now"
                    onclick="buyNow(this)"
                    data-route="{{ route('cart.add', ['product' => $product->id]) }}"
                    data-product-id="{{ $product->id }}">
                    Buy Now
                </button>
                @else
                {{-- Disabled Button --}}
                <button class="btn btn-secondary text-uppercase px-4 py-2" disabled>
                    Not Available
                </button>
                @endif
            </div>

            {{-- Wishlist Toggle (Optional) --}}
            <div class="mt-4">
                <a href="#" class="text-muted text-decoration-none small"
                    data-toggle-route="{{ route('wishlist.toggle', ['product' => $product->id]) }}"
                    onclick="toggleWishlist(this)">
                    <i class="far fa-heart me-1 {{ $isWishlisted ? 'fas is-wishlisted text-danger' : 'far' }}"></i>
                    {{ $isWishlisted ? 'Remove from Wishlist' : 'Add to Wishlist' }}
                </a>
            </div>

        </div>
    </div>


    {{-- ==================================================================== --}}
    {{-- 2. BOTTOM TABS SECTION (Description, Manufacturer, Reviews) --}}
    {{-- ==================================================================== --}}
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs product-tabs border-0" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
                        Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manuf-tab" data-bs-toggle="tab" data-bs-target="#manufacturer" type="button" role="tab" aria-controls="manufacturer" aria-selected="false">
                        Manufacturer
                    </button>
                </li>
            </ul>

            <div class="tab-content border-top p-4" id="productTabsContent">

                {{-- Description Tab Content --}}
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="desc-tab">
                    <p>{{ $product->description }}</p>
                </div>

                {{-- Manufacturer Tab Content --}}
                <div class="tab-pane fade" id="manufacturer" role="tabpanel" aria-labelledby="manuf-tab">
                    @if ($product->manufacturer)
                    <p class="fw-semibold">Manufacturer Details:</p>
                    <p>{{ $product->manufacturer }}</p>
                    @else
                    <p class="text-muted">Manufacturer details are not available for this product.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('styles')
<style>
    :root {
        --theme: #DB4444;
        --theme-dark: #b73232;
        --gold: #f3d9a4;
        --soft-bg: #fafafa;
    }

    /* ---------------------------------------------------
        PRODUCT IMAGE SECTION (Premium Apple-Style Layout)
    --------------------------------------------------- */
    .product-image-gallery {
        width: 100%;
        background: white;
        padding: 30px;
        border-radius: 18px;
        /* border: 1px solid #eee; */
        display: flex;
        justify-content: center;
        align-items: center;
        /* box-shadow: 0px 6px 25px rgba(0, 0, 0, 0.07); */
        /* transition: 0.3s; */
    }

    /* .product-image-gallery:hover {
        transform: translateY(-3px);
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.12);
    } */

    .product-image-gallery img {
        width: 100%;
        max-width: 550px;
        height: 550px;
        object-fit: contain;
        transition: 0.3s ease;
    }

    /* .product-image-gallery img:hover {
        transform: scale(1.07);
    } */

    /* ---------------------------------------------------
        TITLE + PRICE
    --------------------------------------------------- */
    .product-details-actions h1 {
        font-size: 38px;
        font-weight: 800;
        margin-bottom: 12px;
        line-height: 1.2;
    }

    .product-details-actions h2 {
        color: var(--theme);
        font-size: 32px;
        font-weight: 900;
        margin: 18px 0;
    }

    /* ---------------------------------------------------
        SOLD BADGE (SUPER PREMIUM)
    --------------------------------------------------- */
    .sold-badge {
        background: rgba(219, 68, 68, 0.1);
        padding: 8px 18px;
        border-radius: 40px;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 700;
        color: var(--theme);
        border: 1px solid rgba(219, 68, 68, 0.2);
    }

    .sold-badge:before {
        content: "🔥";
        font-size: 18px;
        animation: pulse 1.2s infinite ease-in-out;
    }

    @keyframes pulse {
        50% {
            transform: scale(1.2);
        }
    }

    /* ---------------------------------------------------
        QUANTITY SELECTOR
    --------------------------------------------------- */
    .input-group input {
        height: 42px;
        font-size: 17px;
        border-color: #ddd;
    }

    .input-group button {
        width: 40px;
        border-color: #ddd;
        background: #f8f8f8;
        font-weight: bold;
        transition: 0.25s;
    }

    .input-group button:hover {
        background: var(--theme);
        border-color: var(--theme);
        color: #fff;
    }

    /* ---------------------------------------------------
        BUTTONS (Luxury eCommerce Style)
    --------------------------------------------------- */
    .btn-add-cart {
        background: var(--theme);
        color: #fff;
        padding: 14px 35px !important;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: .5px;
        box-shadow: 0px 4px 12px rgba(219, 68, 68, 0.25);
        transition: 0.3s ease;
    }

    .btn-add-cart:hover {
        background: var(--theme-dark);
        transform: translateY(-4px);
        box-shadow: 0px 6px 18px rgba(219, 68, 68, 0.35);
    }

    .btn-buy-now {
        background: var(--theme);
        color: #fff;
        padding: 14px 35px !important;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: .5px;
        box-shadow: 0px 4px 12px rgba(219, 68, 68, 0.25);
        transition: 0.3s ease;
    }

    .btn-add-cart:hover {
        background: var(--theme-dark);
        transform: translateY(-4px);
        box-shadow: 0px 6px 18px rgba(219, 68, 68, 0.35);
    }


    /* ---------------------------------------------------
        WISHLIST
    --------------------------------------------------- */
    .wishlist-link {
        margin-top: 12px;
        font-size: 15px;
        text-decoration: none;
        color: #777;
        transition: 0.3s;
    }

    .wishlist-link:hover {
        color: var(--theme);
    }

    /* ---------------------------------------------------
        TABS (Premium Underline Tabs)
    --------------------------------------------------- */
    .product-tabs .nav-link {
        padding: 12px 30px;
        background: transparent;
        border: none;
        font-weight: 700;
        color: #444;
        font-size: 16px;
        position: relative;
    }

    .product-tabs .nav-link.active {
        color: var(--theme);
    }

    .product-tabs .nav-link.active::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 20%;
        width: 60%;
        height: 3px;
        background: var(--theme);
        border-radius: 50px;
    }

    .tab-content {
        background: #fff;
        /* border: 1px solid #eee; */
        padding: 28px;
        /* border-radius: 14px; */
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);
        margin-top: -2px;
    }

    /* ---------------------------------------------------
        PAGE SPACING
    --------------------------------------------------- */
    .row.mb-5.pb-5.border-bottom {
        margin-bottom: 40px !important;
        padding-bottom: 40px !important;
    }

    .breadcrumb-item a {
        color: #DB4444 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    window.changeQuantityInput = function(button, delta) {
        const input = button.closest('.input-group').querySelector('input[name="quantity"]');
        let currentQuantity = parseInt(input.value);
        let newQuantity = currentQuantity + delta;

        const maxStockAttr = input.getAttribute('max');
        const maxStock = parseInt(maxStockAttr) || 9999;

        // Check if the current stock is 0
        const isOutOfStock = maxStock <= 0; // Check agar max stock 0 ya usse kam hai

        if (newQuantity >= 1 && newQuantity <= maxStock) {
            // Condition 1: Normal quantity increment
            input.value = newQuantity;
        } else if (isOutOfStock && newQuantity > 0) {
            // Condition 2: Stock 0 hai aur user buy karna chahta hai
            Swal.fire({
                icon: 'error',
                title: 'Currently Unavailable!',
                text: 'This product is temporarily out of stock and cannot be purchased right now.', // ✅ DEDICATED MSG
                confirmButtonColor: '#DB4444'
            });
            input.value = 1; // Quantity ko 1 par hi rakho ya 0 par
        } else if (newQuantity > maxStock && maxStock > 0) {
            // Condition 3: Stock available hai (e.g., 50 units), lekin limit cross ki
            Swal.fire({
                icon: 'warning',
                title: 'Stock Limit Reached!',
                text: `You can only purchase a maximum of ${maxStock} units.`, // ✅ MAX LIMIT MSG
                confirmButtonColor: '#7b68ee'
            });
            input.value = maxStock;
        } else if (newQuantity < 1) {
            // Minimum quantity check
            input.value = 1;
        }
    }
</script>
{{-- Add to Cart and Wishlist toggle functions ke liye shop.js zaroori hai --}}
<script src="{{ asset('js/shop.js') }}"></script>
<script src="{{ asset('js/sweetalert.js') }}"></script>


@endpush