@php use Illuminate\Support\Str; @endphp
@if($wishlistItems->isEmpty())
<p class="text-muted text-center p-5">Your wishlist is empty. Let's start shopping!</p>
@else
<ul class="list-unstyled p-0 m-0">
    @foreach($wishlistItems as $wishlistItem)
    @php
    $product = $wishlistItem->product;
    if (!$product) continue;
    $productLink = !empty($product->slug) ? route('shop.show', ['slug' => $product->slug]) : route('shop.index');
    $imageSource = (!empty($product->image)) ? asset('storage/' . $product->image) : 'https://via.placeholder.com/80x80';
    @endphp

    {{-- ✅ Bootstrap Classes for Item Layout --}}
    <li class="p-3 border-bottom d-flex align-items-center justify-content-between" id="drawer-item-{{ $product->id }}">
        <div class="d-flex align-items-center flex-grow-1">
            <img src="{{ $imageSource }}" alt="{{ $product->name }}" class="me-3 border rounded" style="width: 60px; height: 60px; object-fit: cover;">
            <div>
                <a href="{{ $productLink }}" class="text-dark fw-semibold text-decoration-none d-block mb-1">{{ Str::limit($product->name, 20) }}</a>
                <span class="text-danger fw-bold small">Rs. {{ number_format($product->price, 2) }}</span>
            </div>
        </div>

        {{-- Remove Button --}}
        <button class="btn btn-sm text-muted p-0 remove-wishlist-drawer-btn ms-2"
            data-item-id="{{ $product->id }}"
            data-toggle-route="{{ route('wishlist.toggle', ['product' => $product->id]) }}"
            onclick="toggleWishlist(this)">
            <i class="fas fa-times-circle"></i>
        </button>
    </li>
    @endforeach
</ul>

<span id="drawer-subtotal-data" data-subtotal="{{ number_format($subtotal, 2, '.', '') }}" class="d-none"></span>

@endif