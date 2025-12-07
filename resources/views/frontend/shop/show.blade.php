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

            <div class="d-flex align-items-center mb-3">
                {{-- Rating --}}
                <div class="rating me-3 text-warning">
                    @for ($i = 1; $i <= 5; $i++)
                        @if ($rating>= $i)
                        <i class="fas fa-star"></i>
                        @elseif ($rating > $i - 1)
                        <i class="fas fa-star-half-alt"></i>
                        @else
                        <i class="far fa-star"></i>
                        @endif
                        @endfor
                </div>
                <span class="text-muted me-3">({{ $rating }})</span>
                <span class="text-muted">| {{ $soldCount ?? 0 }} Sold</span>
            </div>

            <h2 class="text-danger fw-bolder mb-4">${{ number_format($product->price, 2) }}</h2>

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
                <button class="btn btn-dark text-uppercase px-4 py-2"
                    {{ $disableControls }} {{-- Agar $isAvailable true hai, toh yeh empty rahega --}}
                    onclick="addToCart(this)"
                    data-route="{{ route('cart.add', ['product' => $product->id]) }}">
                    Add to Cart
                </button>

                {{-- Buy Now Button --}}
                <button class="btn text-uppercase px-4 py-2 fw-semibold"
                    style="background-color: #e0d0a7;"
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
                    <button class="nav-link active" id="desc-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true" style="background-color: #e0d0a7;">
                        Description
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manuf-tab" data-bs-toggle="tab" data-bs-target="#manufacturer" type="button" role="tab" aria-controls="manufacturer" aria-selected="false">
                        Manufacturer
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="false">
                        Reviews ({{ $reviews->count() }})
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

                {{-- Reviews Tab Content --}}
                <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    @forelse($reviews as $review)
                    <div class="review-item border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-1">{{ $review->user->name ?? 'Anonymous User' }}</h6>
                            <div class="text-warning small">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $review->rating >= $i ? '' : 'far' }}"></i>
                                    @endfor
                            </div>
                        </div>
                        <p class="small text-muted">{{ $review->created_at->diffForHumans() }}</p>
                        <p>{{ $review->comment }}</p>
                    </div>
                    @empty
                    <p class="text-muted">No reviews yet. Be the first to review this product!</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

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