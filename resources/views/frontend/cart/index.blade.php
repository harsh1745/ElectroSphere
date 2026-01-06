    {{-- resources/views/frontend/cart/index.blade.php --}}

    @extends('frontend.layouts.app')

    @section('title', 'Shopping Cart')

    @section('content')

    {{-- CSRF token (JS reads this) --}}
    <div id="auth-status" data-is-auth="{!! Auth::check() ? 'true' : 'false' !!}" style="display: none;"></div>

    <div id="csrf-token-data" data-token="{{ csrf_token() }}" style="display:none;"></div>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ol>
            </nav>
        </div>

        @if($cartItems->isEmpty())
        <div class="alert alert-info text-center py-5">Your cart is empty. Let's find something great!</div>
        <div class="text-center">
            <a href="{{ route('shop.index') }}" class="btn btn-lg fw-bold text-white" style="background:#7b68ee;">CONTINUE SHOPPING</a>
        </div>
        @else

        <form id="cart-form">
            <div class="row">
                {{-- LEFT: Cart table --}}
                <div class="col-lg-8">
                    <div class="table-responsive">
                        <table class="table align-middle cart-table">
                            <thead>
                                <tr class="text-uppercase">
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($cartItems as $item)
                                @php
                                $product = $item->product;
                                if (!$product) continue;
                                $maxStock = $product->stock ?? 1;
                                $itemTotal = $product->price * $item->quantity;
                                @endphp

                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-height:100px; width:auto;">
                                    </td>

                                    <td>
                                        <a href="{{ !empty($product->slug) ? route('shop.show', $product->slug) : '#' }}" class="text-dark fw-semibold text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </td>

                                    <td>₹{{ number_format($product->price, 2) }}</td>

                                    <td>
                                        <div class="input-group input-group-sm" style="width:110px;">
                                            {{-- Hidden cart id for auto-save --}}
                                            <input type="hidden" class="cart-id" value="{{ $item->id }}">

                                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(this, -1)">-</button>

                                            <input type="text" class="form-control text-center qty-box"
                                                value="{{ $item->quantity }}"
                                                readonly
                                                data-max="{{ $maxStock }}"
                                                data-price="{{ $product->price }}">

                                            <button class="btn btn-outline-secondary" type="button" onclick="changeQty(this, 1)">+</button>
                                        </div>
                                    </td>

                                    <td class="item-total">₹{{ number_format($itemTotal, 2) }}</td>

                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeItem('{{ $item->id }}')"
                                            style="background:#e74c3c; border-color:#e74c3c; color:#fff;">X</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Keep Continue Shopping --}}
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary fw-bold mt-3">CONTINUE SHOPPING</a>
                </div>

                {{-- RIGHT: Cart totals --}}
                <div class="col-lg-4">
                    <div class="cart-totals-section border p-4 mt-4 mt-lg-0">
                        <h4 class="text-uppercase mb-3">CART TOTALS</h4>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <span>Subtotal</span>
                            <span id="cart-subtotal-display">₹{{ number_format($cartSubtotal, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between fw-bold pb-3 border-bottom">
                            <span>Total</span>
                            <span id="cart-total-display" class="text-danger">₹{{ number_format($cartSubtotal, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="checkout-btn btn w-100 mt-3 text-white fw-bold text-uppercase">
                            PROCEED TO CHECKOUT
                        </a>
                    </div>
                </div>
            </div>
        </form>

        @endif
    </div>

    {{-- ================= SCRIPTS: SweetAlert + Live update + Autosave ================= --}}
    {{-- SweetAlert2 CDN (if you already include it globally, you can remove this) --}}
    <script src="{{ asset('js/sweetalert.js') }}"></script>

    <script>
    // ================================
    // SAFE STUB (Wishlist error avoid)
    // ================================
    if (typeof fetchWishlistContent === 'undefined') {
        function fetchWishlistContent() {
            return;
        }
    }

    // ================================
    // ✅ INR CURRENCY FORMATTER (FIX)
    // ================================
    const fmt = new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2
    });

    // ================================
    // Auto-save debounce map
    // ================================
    const autoSaveTimers = {};

    // ================================
    // UPDATE SUBTOTAL & TOTAL (₹)
    // ================================
    function updateTotals() {
        let subtotal = 0;

        document.querySelectorAll('.item-total').forEach(el => {
            // Remove ₹ and commas before parsing
            const raw = el.textContent
                .replace('₹', '')
                .replace(/,/g, '')
                .trim();

            const val = parseFloat(raw) || 0;
            subtotal += val;
        });

        document.getElementById('cart-subtotal-display').textContent = fmt.format(subtotal);
        document.getElementById('cart-total-display').textContent = fmt.format(subtotal);
    }

    // ================================
    // AUTO SAVE CART ITEM (DEBOUNCED)
    // ================================
    function autoSave(cartId, qty) {
        if (autoSaveTimers[cartId]) {
            clearTimeout(autoSaveTimers[cartId]);
        }

        autoSaveTimers[cartId] = setTimeout(() => {
            const token = document.getElementById('csrf-token-data').dataset.token;

            fetch("{{ route('cart.update') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    quantity: qty
                })
            })
            .then(resp => resp.json().catch(() => {}))
            .then(json => {
                if (json && json.count !== undefined) {
                    const el = document.querySelector('.navbar-cart-count');
                    if (el) el.textContent = json.count;
                }
            })
            .catch(err => console.error('Auto-save error', err));
        }, 500);
    }

    // ================================
    // QUANTITY CHANGE HANDLER
    // ================================
    function changeQty(btn, delta) {
        const box = btn.closest('.input-group').querySelector('.qty-box');
        const cartId = btn.closest('tr').querySelector('.cart-id').value;

        let qty = parseInt(box.value) || 0;
        const max = parseInt(box.dataset.max) || 1;
        const price = parseFloat(box.dataset.price) || 0;

        const newQty = qty + delta;

        // Minimum check
        if (newQty < 1) {
            box.value = 1;
            btn.closest('tr').querySelector('.item-total').textContent = fmt.format(price);
            updateTotals();
            autoSave(cartId, 1);
            return;
        }

        // Max stock check
        if (newQty > max) {
            if (max > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Limit Reached!',
                    text: `You can only purchase a maximum of ${max} units.`,
                    confirmButtonColor: '#7b68ee'
                });

                box.value = max;
                btn.closest('tr').querySelector('.item-total').textContent = fmt.format(price * max);
                updateTotals();
                autoSave(cartId, max);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Currently Unavailable!',
                    text: 'This product is out of stock.',
                    confirmButtonColor: '#DB4444'
                });
                box.value = qty;
            }
            return;
        }

        // Apply new quantity
        box.value = newQty;
        btn.closest('tr').querySelector('.item-total').textContent =
            fmt.format(price * newQty);

        updateTotals();
        autoSave(cartId, newQty);
    }

    // ================================
    // REMOVE ITEM FROM CART
    // ================================
    function removeItem(id) {
        const token = document.getElementById('csrf-token-data').dataset.token;

        Swal.fire({
            title: 'Remove item?',
            text: 'Are you sure you want to remove this item from cart?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7b68ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/cart/remove/${id}`, {
                    method: 'DELETE',
                    headers: {
                        "X-CSRF-TOKEN": token,
                        "Accept": "application/json"
                    }
                })
                .then(() => window.location.reload())
                .catch(() => window.location.reload());
            }
        });
    }

    // ================================
    // INIT ON PAGE LOAD
    // ================================
    document.addEventListener('DOMContentLoaded', () => {
        updateTotals();
    });
</script>

    @endsection
    @push('styles')
    <style>
        :root {
            --theme: #DB4444;
            --theme-dark: #c53a3a;
            --light-red-bg: #ffeaea;
        }

        .breadcrumb-item a {
            color: #DB4444 !important;
        }

        .cart-table th {
            background: var(--light-red-bg);
            color: var(--theme);
            font-weight: 700;
            padding: 14px;
            border: none;
        }

        .cart-table td {
            padding: 16px 12px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f1f1;
        }

        .cart-table img {
            border-radius: 8px;
            background: #fff;
            padding: 8px;
            width: 90px;
            height: 90px;
            object-fit: contain;
        }

        .qty-box {
            font-size: 15px;
            background: #fff;
            border: 1px solid #ddd;
            font-weight: 600;
        }

        .input-group button {
            border-radius: 4px !important;
        }

        /* Remove Button */
        .btn-danger {
            background: var(--theme) !important;
            border-color: var(--theme) !important;
        }

        .btn-danger:hover {
            background: var(--theme-dark) !important;
        }

        /* Cart Totals Box */
        .cart-totals-section {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #f1f1f1;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        }

        .cart-totals-section h4 {
            font-weight: 700;
            letter-spacing: .5px;
            color: var(--theme);
        }

        .cart-totals-section div span {
            font-size: 16px;
        }

        #cart-total-display {
            font-size: 20px;
            font-weight: 700;
            color: var(--theme);
        }



        /* Empty Cart Box */
        .empty-cart-box {
            padding: 60px;
            border-radius: 12px;
            background: #fff4f4;
            border: 1px solid #ffdada;
        }

        .breadcrumb-item.active {
            /* color: var(--theme); */
            font-weight: 600;
        }

        .checkout-btn {
            background: var(--theme);
            border-radius: 50px;
            padding: 14px 20px;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            border: none;
            width: 100%;
            color: white;
            letter-spacing: .5px;
            transition: .25s ease;
            box-shadow: 0 4px 16px rgba(219, 68, 68, 0.3);
        }

        .checkout-btn:hover {
            background: var(--theme-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(219, 68, 68, 0.35);
        }

        .btn-danger {
            background: var(--theme) !important;
            border-color: var(--theme) !important;
            border-radius: 6px;
            font-weight: 700;
            padding: 6px 10px;
            transition: .2s ease;
        }

        .btn-danger:hover {
            background: var(--theme-dark) !important;
            transform: translateY(-2px);
        }
    </style>
    @endpush

    @push('scripts')
    <script src="{{ asset('js/shop.js') }}"></script>
    @endpush