<div class="col-lg-3 col-md-4 col-6">
    <div class="product-card shadow-sm">
        <div class="product-img position-relative overflow-hidden">

            @php
            $placeholderUrl = 'https://via.placeholder.com/300x300/E0E0E0/333333?text=No+Image';
            $img = $product->image ? asset('storage/' . $product->image) : $placeholderUrl;
            @endphp

            <img src="{{ $img }}" class="img-fluid w-100"
                onerror="this.src='{{ $placeholderUrl }}';">

            <div class="wishlist-icon"
                data-toggle-route="{{ route('wishlist.toggle', $product->id) }}"
                onclick="toggleWishlist(this)">
                <i class="wishlist-icon__heart far fa-heart"></i>
            </div>

            <div class="product-overlay d-flex flex-column justify-content-center align-items-center">
                <a href="{{ route('shop.show', $product->slug) }}"
                    class="btn btn-light text-uppercase mb-2">
                    <i class="fas fa-eye me-1"></i> View Details
                </a>

                <button class="btn btn-danger text-uppercase"
                    onclick="addToCart(this)"
                    data-route="{{ route('cart.add', $product->id) }}">
                    <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                </button>
            </div>

        </div>

        <div class="product-info text-center p-3">
            <h6 class="fw-semibold">{{ Str::limit($product->name, 22) }}</h6>
            <p class="text-danger fw-bold">₹{{ number_format($product->price, 2) }}</p>
        </div>
    </div>
</div>