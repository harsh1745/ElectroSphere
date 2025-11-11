@extends('frontend.layouts.app')

@section('title', 'Home')

@section('content')

    <!-- ==================================================================== -->
    <!-- 1. HERO SECTION (STATIC BANNER) -->
    <!-- ==================================================================== -->
    <section id="hero-section" class="hero-section">
        <div class="container container-xl p-0">
            <div class="row align-items-center hero-static-content">

                <!-- Text Content -->
                <div class="col-md-6 col-lg-5 p-4 p-md-5">
                    <span class="badge text-uppercase fw-bold rounded-pill mb-3 py-2 px-3 glass-badge-red">
                        New Launch
                    </span>

                    <h1 class="fw-bolder display-4 mb-3 hero-title">
                        Apple Watch Ultra 2
                    </h1>

                    <p class="lead mb-4 hero-subtitle">
                        Rugged, capable, and built to meet the demands of endurance athletes, outdoor adventurers, and water
                        sports enthusiasts.
                    </p>

                    <a href="#" class="btn btn-lg px-5 py-3 fw-bold rounded-pill shadow-lg glass-btn-red">
                        Discover More
                    </a>
                </div>

                <!-- Image -->
                <div class="col-md-6 col-lg-7 text-center hero-image-col">
                    <img src="{{ asset('images/Iphone-Image.png') }}"
                        onerror="this.onerror=null; this.src='https://placehold.co/800x600/f8f9fa/333333?text=Hero+Product+Image';"
                        alt="Apple Watch Ultra 2" class="img-fluid hero-img">
                </div>

            </div>
        </div>
    </section>


    <!-- ==================================================================== -->
    <!-- 2. CATEGORIES SECTION (Updated as per new image) -->
    <!-- ==================================================================== -->
    <section id="categories-section" class="py-5 bg-white">
        <div class="container container-xl">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div class="d-flex flex-column gap-4">
                        <p class="text-uppercase mb-2 fw-bold categories-label ">Categories</p>
                        <h2 class="fw-bold categories-heading">Browse By Category</h2>
                    </div>
                </div>

                <!-- Arrows -->
                <div class="category-arrows">
                    <button class="arrow-btn left"><i class="fa-solid fa-arrow-left"></i></button>
                    <button class="arrow-btn right"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>

            <!-- Scrollable Categories -->
            <div class="category-scroll-wrapper">
                <div class="category-scroll" id="categoryScroll">

                    <!-- Category Card: Phones -->
                    <div class="category-card text-center" onclick="openCategory('Phones')">
                        <img src="images/Category-CellPhone.svg" alt="Phones" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">Phones</p>
                    </div>

                    <!-- Category Card: Computers -->
                    <div class="category-card text-center" onclick="openCategory('Computers')">
                        <img src="images/Category-Computer.svg" alt="Computers" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">Computers</p>
                    </div>

                    <!-- Category Card: SmartWatch -->
                    <div class="category-card text-center" onclick="openCategory('SmartWatch')">
                        <img src="images/Category-SmartWatch.svg" alt="SmartWatch" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">SmartWatch</p>
                    </div>

                    <!-- Category Card: Camera -->
                    <div class="category-card text-center" onclick="openCategory('Camera')">
                        <img src="images/Category-Camera.svg" alt="Camera" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">Camera</p>
                    </div>

                    <!-- Category Card: Headphones -->
                    <div class="category-card text-center" onclick="openCategory('HeadPhones')">
                        <img src="images/Category-Headphone.svg" alt="HeadPhones" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">HeadPhones</p>
                    </div>

                    <!-- Category Card: Gaming -->
                    <div class="category-card text-center" onclick="openCategory('Gaming')">
                        <img src="images/Category-Gamepad.svg" alt="Gaming" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">Gaming</p>
                    </div>

                    <!-- Category Card: Tablet -->
                    <div class="category-card text-center" onclick="openCategory('Tablet')">
                        <img src="images/Category-Tablet.svg" alt="Tablet" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">Tablet</p>
                    </div>

                    <!-- Category Card: TV -->
                    <div class="category-card text-center" onclick="openCategory('TV')">
                        <img src="images/Category-TV.svg" alt="TV" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">TV</p>
                    </div>

                    <!-- Category Card: Accessories -->
                    <div class="category-card text-center d-none" onclick="openCategory('Accessories')">
                        <img src="images/Category-Accessories.svg" alt="Accessories" class="img-fluid category-img mb-3">
                        <p class="fw-semibold mb-0">Accessories</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Best product selling section -->

    <section class="py-5 bg-white">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <div>
                    <div class="label"><span>This Month</span></div>
                    <h2>Best Selling Products</h2>
                </div>
                <button class="view-all-btn" onclick="window.location.href='products.html'">View All</button>
            </div>
            <!-- Product Grid -->
            <div class="row g-4">
                <!-- Product 1 -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="product-card" onclick="openProduct('north-coat')">
                        <div class="product-actions">
                            <i class="fa-regular fa-heart" onclick="toggleWishlist(event, this)"></i>
                            <i class="fa-solid fa-eye" onclick="openProduct('north-coat'); event.stopPropagation();"></i>
                        </div>
                        <img src="https://i.imgur.com/n1mJk0Q.png" alt="The north coat">
                        <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                        <div class="product-info">
                            <h6>Breed Dry Dog Food</h6>
                            <p><span class="price">$100</span><span class="old-price">$175</span></p>
                        </div>
                    </div>
                </div>

                <!-- Product 2 -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="product-card" onclick="openProduct('gucci-bag')">
                        <div class="product-actions">
                            <i class="fa-regular fa-heart" onclick="toggleWishlist(event, this)"></i>
                            <i class="fa-solid fa-eye" onclick="openProduct('gucci-bag'); event.stopPropagation();"></i>
                        </div>
                        <img src="https://i.imgur.com/6nYHlf1.png" alt="Gucci duffle bag">
                        <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                        <div class="product-info">
                            <h6>Gucci duffle bag</h6>
                            <p><span class="price">$960</span><span class="old-price">$1160</span></p>

                        </div>
                    </div>
                </div>

                <!-- Product 3 -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="product-card" onclick="openProduct('cpu-cooler')">
                        <div class="product-actions">
                            <i class="fa-regular fa-heart" onclick="toggleWishlist(event, this)"></i>
                            <i class="fa-solid fa-eye" onclick="openProduct('cpu-cooler'); event.stopPropagation();"></i>
                        </div>
                        <img src="https://i.imgur.com/z8GuhyT.png" alt="RGB liquid CPU Cooler">
                        <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                        <div class="product-info">
                            <h6>RGB liquid CPU Cooler</h6>
                            <p><span class="price">$160</span><span class="old-price">$170</span></p>

                        </div>
                    </div>
                </div>

                <!-- Product 4 -->
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="product-card" onclick="openProduct('bookshelf')">
                        <div class="product-actions">
                            <i class="fa-regular fa-heart" onclick="toggleWishlist(event, this)"></i>
                            <i class="fa-solid fa-eye" onclick="openProduct('bookshelf'); event.stopPropagation();"></i>
                        </div>
                        <img src="https://i.imgur.com/cybQCNk.png" alt="Small BookShelf">
                        <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                        <div class="product-info">
                            <h6>Small BookShelf</h6>
                            <p><span class="price">$360</span><span class="old-price">$400</span></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- ==================================================================== -->
    <!-- FEATURED ICONS SECTION (Image se match karta hua) -->
    <!-- ==================================================================== -->
    <div class="container-fluid pt-5">
        <div class="row px-xl-5 pb-3">
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="feature-box d-flex align-items-center gap-3">
                    <h1 class="fa fa-check m-0"></h1>
                    <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="feature-box d-flex align-items-center gap-3">
                    <h1 class="fa fa-shipping-fast m-0"></h1>
                    <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="feature-box d-flex align-items-center gap-3">
                    <h1 class="fas fa-exchange-alt m-0"></h1>
                    <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="feature-box d-flex align-items-center gap-3">
                    <h1 class="fa fa-phone-volume m-0"></h1>
                    <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->

    <!-- our product section -->
    <section class="product-section">
        <div class="container">
            <!-- Section Header -->
            <div class="container container-xl">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div class="d-flex flex-column gap-4">
                        <p class="text-uppercase mb-2 fw-bold categories-label">Products</p>
                        <h2 class="fw-bold categories-heading">Explore Our Products</h2>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="row g-4">
                    <!-- Example Product -->
                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>Breed Dry Dog Food</h6>
                                <p class="product-price">$100</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>Canon EOS DSLR</h6>
                                <p class="product-price">$360</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>ASUS Gaming Laptop</h6>
                                <p class="product-price">$700</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>Curology Product Set</h6>
                                <p class="product-price">$500</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>Smart Fitness Watch</h6>
                                <p class="product-price">$250</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>Men’s Running Shoes</h6>
                                <p class="product-price">$150</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>Leather Travel Bag</h6>
                                <p class="product-price">$220</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-12" onclick="openProduct('dogfood')">
                        <div class="product-card">
                            <div class="product-actions">
                                <i class="fa-regular fa-heart wishlist" onclick="toggleWishlist(event, this)"></i>
                                <i class="fa-solid fa-eye" onclick="openProduct('dogfood'); event.stopPropagation();"></i>
                            </div>
                            <img src="https://i.imgur.com/k4tGtqD.png" alt="Dog Food">
                            <div class="add-to-cart" onclick="addToCart(event)">Add To Cart</div>
                            <div class="product-info">
                                <h6>Wireless Headphones</h6>
                                <p class="product-price">$180</p>
                            </div>
                        </div>
                    </div>

                    <!-- Repeat for other products... -->
                </div>

                <!-- View All Button -->
                <div class="text-center">
                    <button class="view-all-btn" onclick="window.location.href='{{route('shop.index')}}'">
                        View All Products
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- END OF our product section -->

    <!-- Start Service Section -->

    <section class="service-section">
        <div class="container">
            <div class="row justify-content-center text-center g-4">
                <div class="section-header">
                    <p class="text-uppercase mb-2 fw-bold categories-label ">Service</p>
                </div>

                <!-- Service 1 -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-box">
                        <div class="icon-ring">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <h5>Free and Fast Delivery</h5>
                        <p>Free delivery for all orders over ₹200</p>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-box">
                        <div class="icon-ring">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h5>24/7 Customer Service</h5>
                        <p>Friendly 24/7 customer support</p>
                    </div>
                </div>

                <!-- Service 3 -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="service-box">
                        <div class="icon-ring">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h5>Money Back Guarantee</h5>
                        <p>We return money within 30 days</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- End Service Section -->


    <!-- Start Footer Section-->

    <footer class="footer-section">
        <div class="container">
            <div class="row gy-4 justify-content-between">

                <!-- Subscribe -->
                <div class="col-lg-2 col-md-6 col-12">
                    <h5>Exclusive</h5>
                    <h7 class="footer-subtitle">Subscribe</h7>
                    <p>Get 10% off your first order</p>
                    <form class="subscribe-form d-none">
                        <input type="email" placeholder="Enter your email" required>
                        <button type="submit"><i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 col-12">
                    <h6>Quick Link</h6>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Product</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Contact US</a></li>
                    </ul>
                </div>

                <!-- Account -->
                <div class="col-lg-2 col-md-6 col-12">
                    <h6>Account</h6>
                    <ul class="footer-links">
                        <li><a href="#">My Account</a></li>
                        <li><a href="#">Login / Register</a></li>
                        <li><a href="#">Cart</a></li>
                        <li><a href="#">Wishlist</a></li>
                        <li><a href="#">Shop</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="col-lg-2 col-md-6 col-12">
                    <h6>Support</h6>
                    <p>111 Bijoy sarani, Dhaka, DH 1515, Bangladesh.</p>
                    <p>exclusive@gmail.com</p>
                    <p>+88015-88888-9999</p>
                </div>

                <!-- Download App -->
                <div class="col-lg-3 col-md-6 col-12">
                    <h6>Follow Us</h6>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom text-center">
            <p>© Copyright ElectroSphere 2025. All rights reserved</p>
        </div>
    </footer>

    <!-- End Footer Section-->

@endsection

@push('styles')
    <!-- Page Specific CSS (Linked from public/css/home.css) -->
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('js/home.js') }}"></script>
@endpush