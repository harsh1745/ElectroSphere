    <!doctype html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet">
        <!-- Font awesome for Icons -->
        <link href="{{ asset('fontawesome/css/all.min.css') }}" rel="stylesheet">


        <!-- Scripts -->
        <!-- Local Bootstrap CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Custom Global CSS (NEW: Linked from public/css/app.css) -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Page Specific Styles (Pushed from individual views like home.blade.php) -->
        @stack('styles')

    </head>

    <body class="d-flex flex-column min-vh-100 justify-content-between">
        <div id="app">

            <!-- 1. Top Black Banner -->
            <!-- ================= TOP INFO BAR ================= -->
            <div class="top-info-bar">
                <div class="container d-flex align-items-center justify-content-between">

                    <!-- Center Promo Text -->
                    <div class="top-info-text mx-auto d-none d-md-block">
                        Summer Sale For All Swim Suits And Free Express Delivery —
                        <strong>OFF 50%</strong>
                        <a href="{{ route('shop.index') }}">ShopNow</a>
                    </div>

                    <!-- Mobile Text -->
                    <div class="top-info-text d-block d-md-none mx-auto">
                        Flat 50% OFF <a href="{{ route('shop.index') }}">Shop</a>
                    </div>

                    <!-- Language -->
                    <!-- <div class="top-info-lang ms-auto">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    English
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Hindi</a></li>
                    <li><a class="dropdown-item" href="#">Spanish</a></li>
                </ul>
            </div>
        </div> -->

                </div>
            </div>
            <!-- ================= TOP INFO BAR END ================= -->


            <!-- 2. Main Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white main-navbar">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        ElectroSphere
                    </a>

                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">

                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 main-nav">
                            <li class="nav-item">
                                {{-- Home Link: isActive('home') use karein --}}
                                <a class="nav-link {{ Request::routeIs('home') || Request::is('/') ? 'active' : '' }}" aria-current="page" href="{{ url('/') }}">Home</a>
                            </li>
                            {{-- Shop Link: Request::routeIs('shop.*') use karein --}}
                            <li class="nav-item">
                                <a class="nav-link {{ Request::routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">Shop</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                            </li>
                            <li class=" nav-item">
                                <a class="nav-link" href="{{ route('about') }}">About</a>
                            </li>
                        </ul>

                        <div class="d-flex align-items-center">
                            <ul class="navbar-nav d-lg-none">
                                @guest
                                @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @endif
                                @else
                                {{-- Mobile mein Logout dikhao agar logged in hai --}}
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                                @endguest
                            </ul>

                            <div class="d-none d-lg-flex align-items-center">
                                <a href="#" class="icon-link position-relative me-4"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#wishlistOffcanvas"
                                    aria-controls="wishlistOffcanvas"
                                    {{-- ✅ FIX: event.preventDefault() aur function call ek saath --}}
                                    onclick="event.preventDefault(); fetchWishlistContent(event);">
                                    <i class="far fa-heart"></i>
                                    <span id="wishlist-count" class="badge wishlist-badge rounded-pill" style="color:black;">
                                        {{ Auth::check() ? App\Http\Controllers\Frontend\WishlistController::getWishlistCount() : 0 }}
                                    </span>
                                </a>
                                {{-- CART ICON --}}
                                <a href="{{ route('cart.index') }}" class="icon-link position-relative" title="Cart">
                                    {{-- ✅ Basket Icon --}}
                                    <i class="fas fa-shopping-cart"></i>

                                    {{-- ✅ Cart Count Display --}}
                                    <span id="cart-count" class="badge wishlist-badge rounded-pill" style="color:black; right: -5px;">
                                        {{ $cartCount }}
                                    </span>
                                </a>
                            </div>

                            {{-- 2. USER DROPDOWN (ONLY when AUTH) --}}
                            {{-- User Dropdown (Visible only when logged in) --}}
                            @auth
                            <li class="nav-item dropdown list-unstyled">
                                <a id="navbarDropdown" class="nav-link icon-link p-0 d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre style="margin-left: 1.25rem;">
                                    <div class="user-avatar-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="navbarDropdown">

                                    {{-- 1. Manage My Account (Home/Profile Page) --}}
                                    <a class="dropdown-item" href="{{ route('account.index') }}">
                                        <i class="fas fa-user me-2"></i> {{ __('Manage My Account') }}
                                    </a>

                                    @if (Route::has('orders.index'))
                                    <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        <i class="fas fa-box"></i> {{ __('My Orders') }}
                                    </a>
                                    @else
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-box"></i> {{ __('My Orders') }} (Coming Soon)
                                    </a>
                                    @endif

                                    {{-- 5. Logout Link --}}
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                    </a>

                                    {{-- Hidden Logout Form (Required for POST request) --}}
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>

                                </div>
                            </li>
                            @endauth

                            {{-- 3. DESKTOP LOGIN LINK (Show ONLY when GUEST) --}}
                            @guest
                            @if (Route::has('login'))
                            <li class="nav-item list-unstyled d-none d-lg-block ms-3">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @endif
                            @endguest

                            {{-- 4. LOGOUT FORM (For both desktop dropdown and mobile link) --}}
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <main class="py-0 flex-grow-1">
                @yield('content')
            </main>
            <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
                <div id="cartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong class="me-auto">Cart Updated</strong>
                        <small class="text-white">Just Now</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body" id="cartToastBody">
                        Product added to your cart successfully!
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="wishlistOffcanvas" aria-labelledby="wishlistOffcanvasLabel">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title fw-bold" id="wishlistOffcanvasLabel">💖 My Wishlist Items</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column p-0">
                {{-- Content Yahan Load Hoga --}}
                <div class="flex-grow-1" id="wishlist-drawer-content">
                    <p class="text-muted text-center p-5">Loading...</p>
                </div>
            </div>

            <div class="offcanvas-footer p-3 border-top">
                <div class="d-flex justify-content-between mb-3">
                    <span class="fw-semibold">Subtotal:</span>
                    <span class="fw-bold text-danger" id="wishlist-subtotal">Rs. 0.00</span>
                </div>
                <a href="{{ route('wishlist.index') }}" class="btn w-100 text-white fw-semibold" style="background-color: #7b68ee;">View Full Wishlist</a>
            </div>
        </div>
        @include('frontend.layouts.footer')

        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/popper.min.js') }}"></script>
        <script src="{{ asset('js/sweetalert.js') }}"></script>
        @stack('scripts')

    </body>

    </html>