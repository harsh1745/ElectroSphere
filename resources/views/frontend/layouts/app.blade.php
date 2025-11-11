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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    <!-- Local Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom Global CSS (NEW: Linked from public/css/app.css) -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Page Specific Styles (Pushed from individual views like home.blade.php) -->
    @stack('styles')

</head>

<body>
    <div id="app">

        <!-- 1. Top Black Banner -->
        <div class="top-banner d-none">
            <div class="container d-flex justify-content-end align-items-center">
                <!-- Banner Text -->
                <span class="text-center mx-auto d-none d-md-block">
                    Summer Sale For All Swim Suits And Free Express Delivery - OFF 50%! <a href="#" class="text-white text-decoration-underline ms-2">ShopNow</a>
                </span>

                <!-- Language Dropdown -->
                <div class="ms-auto me-0">
                    <div class="dropdown">
                        <a class="text-white dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            English
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" style="font-size: 0.8rem;">
                            <li><a class="dropdown-item" href="#">Hindi</a></li>
                            <li><a class="dropdown-item" href="#">Spanish</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Main Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white main-navbar">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">ElectroSphere</a>

    <button
      class="navbar-toggler border-0"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="{{ __('Toggle navigation') }}"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 main-nav">
        <!-- HOME -->
        <li class="nav-item">
          <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
        </li>

        <!-- ABOUT -->
        <li class="nav-item">
          <a class="nav-link {{ Request::is('about') ? 'active' : '' }}" href="{{ url('/about') }}">About</a>
        </li>

        <!-- SHOP -->
        <li class="nav-item">
          <a class="nav-link {{ Request::routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">Shop</a>
        </li>

        <!-- CONTACT -->
        <li class="nav-item">
          <a class="nav-link {{ Request::is('contact') ? 'active' : '' }}" href="{{ url('/contact') }}">Contact</a>
        </li>
      </ul>

      <!-- Icons, user dropdown, etc. (unchanged) -->
      <div class="d-flex align-items-center">
        <div class="search-container me-4 d-none d-lg-block">
          <input
            class="form-control search-input"
            type="search"
            placeholder="What are you looking for?"
            aria-label="Search"
          />
          <i class="fas fa-search search-icon"></i>
        </div>

        <div class="d-none d-lg-flex align-items-center">
          <a href="#" class="icon-link" title="Wishlist"><i class="far fa-heart"></i></a>
          <a href="#" class="icon-link position-relative" title="Cart"><i class="fas fa-shopping-cart"></i></a>
        </div>

        @auth
        <li class="nav-item dropdown list-unstyled">
          <a
            id="navbarDropdown"
            class="nav-link icon-link p-0 d-flex align-items-center"
            href="#"
            role="button"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            style="margin-left: 1.25rem;"
          >
            <div class="user-avatar-icon"><i class="fas fa-user"></i></div>
          </a>

          <div class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('home') }}">
              <i class="fas fa-user"></i> Manage My Account
            </a>
            @if (Route::has('orders.index'))
            <a class="dropdown-item" href="{{ route('orders.index') }}">
              <i class="fas fa-box"></i> My Order
            </a>
            @endif

            <a
              class="dropdown-item"
              href="{{ route('logout') }}"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            >
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
          </div>
        </li>
        @endauth

        @guest
        @if (Route::has('login'))
        <li class="nav-item list-unstyled d-none d-lg-block ms-3">
          <a class="nav-link" href="{{ route('login') }}">Login</a>
        </li>
        @endif
        @endguest
      </div>
    </div>
  </div>
</nav>

<main class="py-0">@yield('content')</main>

<


    <!-- Local Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    @stack('scripts')

</body>

</html>