<footer class="footer-section text-light pt-5 pb-4 mt-5">
    <div class="container container-xl">

        <div class="row gy-5">

            <!-- BRAND INFO -->
            <div class="col-lg-4 col-md-6">
                <h3 class="fw-bold mb-3">YourStore</h3>
                <p class="text-light-50">
                    India’s most trusted store for premium electronics & gadgets.
                </p>

                <div class="d-flex gap-3 mt-3 social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>

            <!-- SITE LINKS -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3">Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <!-- USER LINKS -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3">My Account</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('account.index') }}">My Account</a></li>
                    <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                    <li><a href="{{ route('cart.index') }}">Cart</a></li>
                    <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                </ul>
            </div>

        </div>

        <hr class="mt-5 border-secondary">

        <p class="text-center text-light-50 mt-3 mb-0 small">
            © {{ date('Y') }} YourStore. All Rights Reserved.
        </p>

    </div>
</footer>


<style>
    .footer-section {
        background: #0d0d0d;
        padding-top: 70px;
        padding-bottom: 40px;
    }

    .brand-title {
        font-size: 1.9rem;
        letter-spacing: 0.5px;
    }

    .footer-desc {
        font-size: 0.95rem;
        line-height: 1.6;
        max-width: 320px;
    }

    .footer-heading {
        color: #fff;
        margin-bottom: 20px !important;
        font-size: 1.2rem;
    }

    .footer-links {
        list-style: none;
        padding-left: 0;
    }

    .footer-links li {
        margin-bottom: 10px;
    }

    .footer-links a {
        color: #bcbcbc;
        text-decoration: none;
        transition: 0.25s ease;
        font-size: 0.95rem;
    }

    .footer-links a:hover {
        color: #fff;
        padding-left: 6px;
    }

    .social-icons a {
        color: #fff;
        font-size: 1.3rem;
        transition: 0.3s ease;
    }

    .social-icons a:hover {
        color: #DB4444;
        transform: translateY(-3px);
    }

    .text-light-50 {
        color: #bbbbbb !important;
    }

    @media (max-width: 576px) {
        .brand-title {
            font-size: 1.6rem;
        }
    }
</style>