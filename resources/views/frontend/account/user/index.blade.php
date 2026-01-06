@extends('frontend.layouts.app')

@section('title', 'My Account')
<div id="auth-status" data-is-auth="{{ Auth::check() ? 'true' : 'false' }}" style="display:none;"></div>
<div id="csrf-token-data" data-token="{{ csrf_token() }}" style="display:none;"></div>

@section('content')

<div class="container py-5 account-container">

    <div class="row">

        <!-- ================= LEFT SIDEBAR ================= -->
        <div class="col-lg-3 mb-4">
            <div class="sidebar-card shadow-sm p-4 rounded-4">

                <div class="profile-avatar-big mb-3">
                    <i class="fa fa-user"></i>
                </div>

                <h5 class="fw-bold text-center">{{ $user->name }}</h5>
                <p class="text-muted text-center small">{{ $user->email }}</p>

                <hr>

                <ul class="account-menu">

                    {{-- My Profile --}}
                    <li class="{{ Request::routeIs('account.index') ? 'active' : '' }}">
                        <a href="{{ route('account.index') }}">
                            <i class="fa fa-user me-2"></i> My Profile
                        </a>
                    </li>

                    {{-- Wishlist --}}
                    <li class="{{ Request::routeIs('wishlist.index') ? 'active' : '' }}">
                        <a href="{{ route('wishlist.index') }}">
                            <i class="fa fa-heart me-2"></i> Wishlist
                        </a>
                    </li>

                    {{-- My Orders --}}
                    <li class="{{ Request::routeIs('orders.index') ? 'active' : '' }}">
                        <a href="{{ route('orders.index') }}">
                            <i class="fa fa-shopping-bag me-2"></i> My Orders
                        </a>
                    </li>

                    {{-- Logout --}}
                    <li>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out me-2"></i> Logout
                        </a>
                    </li>

                </ul>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>


            </div>
        </div>

        <!-- ================= RIGHT MAIN PANEL ================= -->
        <div class="col-lg-9">

            <div class="profile-card shadow-sm p-4 rounded-4">

                <h3 class="fw-bold mb-4">Account Details</h3>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Full Name</label>
                        <div class="info-box">{{ $user->name }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email Address</label>
                        <div class="info-box">{{ $user->email }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Date of Birth</label>
                        <div class="info-box">{{ $user->date_of_birth ?? 'Not added' }}</div>
                    </div>

                </div>

                <a href="{{ route('account.edit') }}" class="theme-btn mt-4 w-100">
                    Edit Profile
                </a>

            </div>

        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
    :root {
        --theme-red: #DB4444;
        --theme-red-dark: #b93636;
    }

    /* MAIN LAYOUT */
    .account-container {
        max-width: 1200px;
    }

    .account-menu li a {
        color: inherit;
        text-decoration: none;
        display: block;
    }

    /* LEFT SIDEBAR */
    .sidebar-card {
        border: 1px solid #eee;
        background: #fff;
        border-radius: 12px;
    }

    .profile-avatar-big {
        width: 90px;
        height: 90px;
        margin: auto;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--theme-red);
        color: #fff;
        border-radius: 50%;
        font-size: 32px;
    }

    .account-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .account-menu li {
        padding: 10px 14px;
        margin-bottom: 6px;
        border-radius: 8px;
        cursor: pointer;
        transition: .2s ease;
    }

    .account-menu li:hover {
        background: #f9d3d3;
        color: var(--theme-red);
    }

    .account-menu .active {
        background: var(--theme-red);
        color: #fff !important;
        font-weight: bold;
    }

    /* RIGHT SIDE CARD */
    .profile-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eee;
    }

    .info-box {
        background: #fafafa;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #e6e6e6;
        font-weight: 500;
    }

    /* BTN */
    .theme-btn {
        background: var(--theme-red);
        border-radius: 50px;
        padding: 12px 20px;
        color: #fff;
        text-align: center;
        display: inline-block;
        font-weight: 600;
        transition: .2s ease;
    }

    .theme-btn:hover {
        background: var(--theme-red-dark);
        color: #fff;
        transform: translateY(-2px);
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .profile-avatar-big {
            width: 70px;
            height: 70px;
            font-size: 26px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/shop.js') }}"></script>
@endpush