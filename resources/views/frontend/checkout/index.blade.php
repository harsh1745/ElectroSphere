@extends('frontend.layouts.app')

@section('title', 'Checkout')

@section('content')
<div id="auth-status" data-is-auth="{!! Auth::check() ? 'true' : 'false' !!}" style="display: none;"></div>

<div class="container my-5">

    {{-- SweetAlert Session Messages --}}
    @if(session('success'))
    <input type="hidden" id="swal-success" value="{{ session('success') }}">
    @endif
    @if(session('error'))
    <input type="hidden" id="swal-error" value="{{ session('error') }}">
    @endif


    <div class="row">

        @php
        $shippingCost = $subtotal >= 5000 ? 0.00 : 177.00;
        $finalTotal = $subtotal + $shippingCost;
        @endphp

        {{-- Left Column: Shipping & Payment (col-lg-8) --}}
        <div class="col-lg-8">

            {{-- ⭐ SHIPPING ADDRESS SECTION OUTSIDE MAIN FORM --}}
            <h2 class="fw-bold mb-4">Your Shipping Address</h2>

            <div class="shipping-address-content border p-3 mb-4 rounded">

                @forelse($addresses as $address)
                <div class="form-check mb-3 p-3 border rounded">

                    {{-- RADIO --}}
                    <input class="form-check-input"
                        type="radio"
                        name="selected_address"
                        form="checkout_form"
                        id="address_{{ $address->id }}"
                        value="{{ $address->id }}"
                        @if(old('selected_address', $loop->first ? $address->id : null) == $address->id) checked @endif>

                    <label class="form-check-label fw-semibold" for="address_{{ $address->id }}">
                        {{ $address->full_name }} ({{ $address->phone }})
                    </label>

                    <p class="small text-muted ms-4 mb-0">
                        {{ $address->street_address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->zip_code }}
                    </p>

                    <div class="text-end mt-3 d-flex gap-2 justify-content-end">

                        {{-- EDIT BUTTON --}}
                        <button type="button" style="height: 31px;" class="btn btn-sm btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#editAddressModal_{{ $address->id }}">
                            Edit
                        </button>

                        {{-- DELETE (Separate Form) --}}
                        <form action="{{ route('user.address.delete', $address->id) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Delete this Address?')">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- EDIT MODAL --}}
                <div class="modal fade" id="editAddressModal_{{ $address->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <form action="{{ route('user.address.update', $address->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">Edit Address</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Full Name</label>
                                            <input type="text" name="full_name" value="{{ $address->full_name }}" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">Phone Number</label>
                                            <input type="text" name="phone" value="{{ $address->phone }}" class="form-control" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-semibold">Street Address</label>
                                            <input type="text" name="street_address" value="{{ $address->street_address }}" class="form-control" required>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label fw-semibold">Apartment / Suite</label>
                                            <input type="text" name="apartment_suite" value="{{ $address->apartment_suite }}" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">City</label>
                                            <input type="text" name="city" value="{{ $address->city }}" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">State</label>
                                            <input type="text" name="state" value="{{ $address->state }}" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">Zip Code</label>
                                            <input type="text" name="zip_code" value="{{ $address->zip_code }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-dark">Update Address</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                @empty
                <p class="alert alert-warning">Please add an address before proceeding to checkout.</p>
                @endforelse

                {{-- ADD NEW --}}
                @if($addresses->count() < 2)
                    <button type="button" class="btn btn-dark mt-3"
                    data-bs-toggle="modal"
                    data-bs-target="#addAddressModal">
                    + Add New Address
                    </button>
                    @endif

            </div>


            {{-- ⭐ MAIN CHECKOUT FORM (SEPARATE) --}}
            <form id="checkout_form" method="POST" action="{{ route('checkout.process') }}">
                @csrf

                {{-- hidden radio validation --}}
                @error('selected_address')
                <div class="text-danger small mb-2">{{ $message }}</div>
                @enderror

                {{-- PAYMENT METHOD --}}
                <h2 class="fw-bold my-4 pt-3 border-top">Payment Method</h2>
                <div class="payment-method-content p-3 border rounded">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                        <label class="form-check-label fw-semibold">
                            Cash on Delivery (COD)
                        </label>
                    </div>
                </div>

                {{-- CAPTCHA --}}
                <div class="captcha-check p-3 border rounded mt-4">
                    <label class="form-label fw-bold">
                        Security Check: <span class="text-danger">{{ $captcha_question }}</span>
                    </label>
                    <input type="text" class="form-control" name="captcha_answer" required>
                </div>

                {{-- SUBMIT --}}
                {{-- 4. CONFIRM BUTTON SECTION --}}
                <div class="d-flex justify-content-between align-items-center mt-5 p-4 border rounded" style="background-color: #f8f9fa;">

                    {{-- Buyer Protection (Left: Aligned with icon) --}}
                    <div class="d-flex align-items-start gap-3">
                        <i class="fas fa-shield-alt fa-2x text-primary mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Buyer Protection</h6>
                            <small class="text-muted">Full Refund if you don't receive your order</small><br>
                            <small class="text-muted">Refund if item not as described</small>
                        </div>
                    </div>

                    {{-- Confirm & Pay (Right: Contains Total and Button) --}}
                    <div class="text-end">
                        <div class="mb-3">
                            <h5 class="fw-bold mb-1">
                                All Total:
                                <span class="text-success">₹{{ number_format($finalTotal, 2) }}</span>
                            </h5>
                            <p class="text-muted small">By clicking 'Confirm & Pay', you agree to the terms.</p>
                        </div>

                        {{-- YEH HAI AAPKA BUTTON --}}
                        <button type="submit" class="btn btn-lg text-white" style="background-color: #4CAF50;">
                            Confirm & Pay
                        </button>
                    </div>
                </div>

            </form>
        </div>


        {{-- RIGHT COLUMN (Order Summary) --}}
        <div class="col-lg-4">
            <div class="order-summary-section border p-4 mt-4 mt-lg-0">
                <h4 class="text-uppercase mb-3 fw-bold">Order Summary</h4>
                <ul class="list-group list-group-flush mb-3">
                    @foreach($cartItems as $item)
                    @php $product = $item->product; if (!$product) continue; @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <div class="flex-grow-1 me-2">
                            <span class="fw-semibold">{{ Str::limit($product->name, 25) }}</span>
                            <span class="text-muted small">x{{ $item->quantity }}</span>
                        </div>
                        <span class="fw-semibold">₹{{ number_format($product->price * $item->quantity, 2) }}</span>
                    </li>
                    @endforeach
                </ul>

                <div class="d-flex justify-content-between border-top pt-3">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Shipping</span>
                    <span>
                        @if ($shippingCost == 0.00)
                        <span class="text-success fw-bold">Free</span>
                        @else
                        ₹{{ number_format($shippingCost, 2) }}
                        @endif
                    </span>
                </div>

                <div class="d-flex justify-content-between fw-bold py-3 border-top mt-2">
                    <span>Total Amount</span>
                    <span class="text-danger">₹{{ number_format($finalTotal, 2) }}</span>
                </div>
            </div>
            <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary fw-bold mt-4">
                CONTINUE SHOPPING
            </a>
        </div>

    </div>
</div>
@endsection

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="addAddressModalLabel">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('user.address.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Street Address</label>
                            <input type="text" name="street_address" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Apartment / Suite (optional)</label>
                            <input type="text" name="apartment_suite" class="form-control">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">City</label>
                            <input type="text" name="city" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">State</label>
                            <input type="text" name="state" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Zip Code</label>
                            <input type="text" name="zip_code" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-dark">Save Address</button>
                </div>
            </form>

        </div>
    </div>
</div>

@push('styles')
<style>
    :root {
        --theme: #DB4444;
        --theme-dark: #C53A3A;
        --light-bg: #FFF1F1;
        --soft-border: #ffd4d4;
    }

    /* ===========================================
    HEADINGS
=========================================== */
    h2.fw-bold,
    h4.fw-bold {
        color: var(--theme);
    }

    /* ===========================================
    ADDRESS CARD STYLING
=========================================== */
    .shipping-address-content .form-check {
        background: #ffffff;
        border: 1px solid var(--soft-border);
        border-radius: 12px;
        transition: .25s ease;
    }

    .shipping-address-content .form-check:hover {
        border-color: var(--theme);
        box-shadow: 0 4px 12px rgba(219, 68, 68, 0.18);
    }

    .shipping-address-content .form-check-input:checked {
        background-color: var(--theme);
        border-color: var(--theme);
    }

    .btn-outline-primary {
        border-color: var(--theme);
        color: var(--theme);
    }

    .btn-outline-primary:hover {
        background: var(--theme);
        color: white;
    }

    .btn-outline-danger {
        border-color: var(--theme);
        color: var(--theme);
    }

    .btn-outline-danger:hover {
        background: var(--theme);
        color: white;
    }

    /* ===========================================
    ADD ADDRESS BUTTON
=========================================== */
    .btn.btn-dark {
        background: var(--theme) !important;
        border-color: var(--theme) !important;
    }

    .btn.btn-dark:hover {
        background: var(--theme-dark) !important;
    }

    /* ===========================================
    PAYMENT METHOD BOX
=========================================== */
    .payment-method-content {
        border-color: var(--soft-border) !important;
        background: #fff;
        border-radius: 10px;
    }

    .payment-method-content .form-check-input:checked {
        background-color: var(--theme);
        border-color: var(--theme);
    }

    /* ===========================================
    CAPTCHA BOX
=========================================== */
    .captcha-check {
        border-color: var(--soft-border) !important;
        background: #fff;
        border-radius: 10px;
    }

    .captcha-check label {
        color: var(--theme);
    }

    /* ===========================================
    BUYER PROTECTION SECTION
=========================================== */
    .d-flex.align-items-start.gap-3 i {
        color: var(--theme) !important;
    }

    .d-flex.align-items-start.gap-3 small {
        font-size: 13px;
    }

    /* ===========================================
    CONFIRM & PAY BUTTON (PREMIUM)
=========================================== */
    #checkout_form button[type="submit"] {
        background: linear-gradient(90deg, #DB4444, #C53A3A);
        border: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-weight: 700;
        color: white;
        letter-spacing: .5px;
        transition: .25s ease;
    }

    #checkout_form button[type="submit"]:hover {
        background: linear-gradient(90deg, #C53A3A, #DB4444);
        transform: translateY(-2px);
    }

    /* ===========================================
    ORDER SUMMARY BOX
=========================================== */
    .order-summary-section {
        border-radius: 14px;
        border-color: var(--soft-border) !important;
        box-shadow: 0 6px 18px rgba(219, 68, 68, 0.10);
    }

    .order-summary-section h4 {
        color: var(--theme);
        font-weight: 800;
    }

    .order-summary-section span.text-danger {
        color: var(--theme) !important;
        font-size: 18px;
        font-weight: 700;
    }

    .list-group-item {
        border: none;
        padding: 14px 0 !important;
        font-size: 15px;
    }

    /* Continue shopping */
    .btn-outline-secondary {
        border-radius: 10px;
        border-color: var(--theme) !important;
        color: var(--theme) !important;
        font-weight: 600;
    }

    .btn-outline-secondary:hover {
        background: var(--theme);
        color: white !important;
    }

    /* Modal Styling */
    .modal-header {
        background: var(--light-bg);
        border-bottom: 1px solid var(--soft-border);
    }

    .modal-title {
        color: var(--theme);
    }

    /* Inputs */
    .form-control:focus {
        border-color: var(--theme);
        box-shadow: 0 0 4px rgba(219, 68, 68, 0.4);
    }
</style>

@endpush
@push('scripts')
<script src="{{ asset('js/sweetalert.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        let successMsg = document.getElementById("swal-success");
        let errorMsg = document.getElementById("swal-error");

        if (successMsg) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMsg.value,
                confirmButtonColor: '#7b68ee'
            });
        }

        if (errorMsg) {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: errorMsg.value,
                confirmButtonColor: '#d33'
            });
        }
    });
</script>

<script src="{{ asset('js/shop.js') }}"></script>
@endpush