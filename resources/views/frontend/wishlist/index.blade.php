{{-- resources/views/frontend/wishlist/index.blade.php --}}

@extends('frontend.layouts.app')

@section('title', 'My Wishlist')

@section('content')

<div id="auth-status" data-is-auth="{!! Auth::check() ? 'true' : 'false' !!}" style="display: none;"></div>
<div id="csrf-token-data" data-token="{!! csrf_token() !!}" style="display: none;"></div>

<div class="container my-5">

    <p class="breadcrumb-text mb-4">
        <a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a> / Wishlist
    </p>

    <div class="row">
        <div class="col-lg-12">

            {{--  ⭐ EMPTY BOX UI (Hidden unless empty) --}}
            <div id="wishlist-empty-box"
     @if($wishlistItems->isEmpty()) style="display:block;" @else style="display:none;" @endif>

                <div class="alert alert-info text-center mt-5">
                    Your wishlist is empty. Let's start shopping!
                </div>
                <div class="text-center mt-4">
                    <a id="wishlist-empty-button" href="{{ route('shop.index') }}" class="btn btn-dark">Shop Now</a>
                </div>
            </div>

            {{-- ⭐ WISHLIST TABLE WRAPPER --}}
            <div id="wishlist-table-wrapper"
     @if($wishlistItems->isEmpty()) style="display:none;" @else style="display:block;" @endif>
                <div class="table-responsive">
                    <table class="table align-middle wishlist-table" style="min-width: 600px;">
                        <thead>
                            <tr class="text-uppercase">
                                <th>Image</th>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Action</th>
                                <th>Remove</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($wishlistItems as $wishlistItem)
                                @php
                                    $product = $wishlistItem->product;
                                    if (!$product) continue;
                                @endphp

                                <tr id="wishlist-row-{{ $product->id }}">
                                    <td>
                                        <img src="{{ asset('storage/'.$product->image) }}" style="max-height:100px;">
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>

                                    <td>
                                        <button class="btn btn-sm text-white"
                                            style="background:#7b68ee"
                                            data-product-id="{{ $product->id }}"
                                            data-cart-route="{{ route('cart.add', $product->id) }}"
                                            data-wishlist-route="{{ route('wishlist.toggle', $product->id) }}"
                                            onclick="moveFromWishlistToCart(this)">
                                            Add to Cart
                                        </button>
                                    </td>

                                    <td>
                                        <button class="btn btn-sm text-white"
                                            style="background:#7b68ee"
                                            data-toggle-route="{{ route('wishlist.toggle', $product->id) }}"
                                            onclick="toggleWishlist(this.closest('tr'))">
                                            X
                                        </button>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between my-5">
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-dark fw-semibold">
                        CONTINUE SHOPPING
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
@push('styles')
<style>
    /* Smooth fade-out for wrapper or rows */
    .fade-out {
        opacity: 0;
        transition: opacity 0.4s ease-out, transform 0.4s ease-out;
        transform: translateY(-6px);
    }

    /* Smooth fade-in for empty-state */
    .fade-in {
        opacity: 0;
        animation: fadeInAnim 0.5s forwards ease-out;
    }

    @keyframes fadeInAnim {
        from {
            opacity: 0;
            transform: translateY(8px);
        }

        to {
            opacity: 1;
            transform: translateY(0px);
        }
    }

    /* Row-level fade when removing a single row */
    .row-fade-out {
        opacity: 0;
        transition: opacity 0.35s ease-out, height 0.3s ease-out, padding 0.3s ease-out, margin 0.3s ease-out;
        height: 0 !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
</style>
@endpush


@push('scripts')
<script src="{{ asset('js/shop.js') }}"></script>
@endpush