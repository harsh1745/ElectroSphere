<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Auth\LoginController; // ✅ YEH LINE ADD KARO
use App\Http\Controllers\Frontend\ShopController; // Ensure this is the correct path
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ReviewController; // ✅ NEW: Review Controller
use App\Http\Controllers\Frontend\CheckoutController; // ✅ NEW: Checkout Controller
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\AddressController; // Naya Controller import karein
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Frontend\InvoiceController; // ✅ Make sure this controller exists and is imported
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Yeh file tumhare saare web routes ko register karti hai.
|
*/

// --- DEFAULT USER AUTH & HOME ---
Route::get('/', function () {
    return redirect('/home');
});

// ✅ 1. YEH PUBLIC HOME PAGE HOGA (Yeh block sahi hai)
Route::get('/home', function () {
    // Tum yahaan products fetch kar sakte ho, ya seedha view return kar sakte ho
    return view('frontend.Home.home');
})->name('home');

// ✅ YEH HAI PUBLIC HOME PAGE (Kahan tum products dikhaoge)
// Route::get('/home', function () {
//     // Tum yahaan products fetch kar sakte ho, ya seedha view return kar sakte ho
//     return view('home');
// })->name('home');

// Default user login/register, password reset disable hai.
Auth::routes(['reset' => false, 'email' => false, 'verify' => true]);
// Route::middleware(['auth'])->group(function () {
//     Route::get('/home', function () {
//         return view('home');
//     })->name('home');
// });


// ✅ CUSTOMER LOGOUT ROUTE ADD KARO
// Auth::routes() se GET/logout hata diya jata hai, so POST ko explicitly define karna padta hai
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// --- CUSTOM PASSWORD RESET FLOW (User Side) ---
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'verifyDobAndRedirect'])->name('password.email');
Route::get('reset-password-custom/{email}', [ResetPasswordController::class, 'showResetFormCustom'])->name('password.reset_custom');
Route::post('password/reset', [ResetPasswordController::class, 'resetPasswordCustom'])->name('password.update');


// --- ADMIN LOGIN/LOGOUT (Unprotected) ---
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


// --- IMAGE STREAMING ROUTE (Publicly accessible to serve images) ---
Route::get('/storage/products/{filename}', function ($filename) {
    $path = 'public/products/' . $filename;

    if (!Storage::exists($path)) {
        abort(404);
    }

    // File ko read karke browser ko stream karna (403 Forbidden error ko fix karta hai)
    return Storage::response($path);
})->name('storage.product.show');


// --- WISHLIST API (Requires new WishlistController) ---
Route::middleware(['auth'])->group(function () {
    // ✅ FIX: Imported Controller class name use kiya gaya
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');
    // ✅ NEW: Wishlist Index Route (List of all wishlisted products)
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    // ✅ NEW: AJAX route to fetch sidebar content
    Route::get('/wishlist/drawer-content', [WishlistController::class, 'getDrawerContent'])->name('wishlist.drawer.content');

    // ✅ NEW CART ROUTES

    // 1. Full Cart Page (GET)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // 2. Add Item (POST AJAX)
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

    // 3. Update Cart Quantities (POST Form submission from cart page)
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

    // 4. Remove Item (DELETE/AJAX from Cart Page)
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // ✅ FIX: Instant Quantity Update Route (for AJAX)
    Route::post('/cart/update-item/{cartItem}', [CartController::class, 'updateItemQuantity'])->name('cart.update.item');

    // 🎁 NEW: COUPON APPLICATION ROUTE
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');

    // 📝 NEW: REVIEW SUBMISSION ROUTE
    Route::post('/reviews/submit/{product}', [ReviewController::class, 'store'])->name('reviews.store');


    // 🛒 NEW: CHECKOUT ROUTE (Used by Buy Now action)
    // NOTE: Replace CheckoutController with your actual controller if different
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // 1. Checkout Page Display Karna (GET)
    // Yeh route 'frontend.checkout.index' view ko load karega
    Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])
        ->name('checkout.index');

    // 2. Order Process Karna (POST)
    // Yeh route form submission ko handle karega
    Route::post('/checkout/process', [App\Http\Controllers\Frontend\CheckoutController::class, 'process'])
        ->name('checkout.process');

    // Address Store Route ko Frontend\AddressController par point karein
    // Route::post('/user/address', [AddressController::class, 'store'])->name('user.address.store');
    // Address Store
    Route::post('/user/address', [AddressController::class, 'store'])->name('user.address.store');

    Route::put('/user/address/{id}', [AddressController::class, 'update'])->name('user.address.update');

    Route::delete('/user/address/{id}', [AddressController::class, 'destroy'])->name('user.address.delete');





    // 1. My Orders (Where all orders are listed)
    // 1. My Orders (Orders List Page)
    // ✅ Yahan hum wohi method use kar sakte hain jo Invoices ke liye bana hai
    // Route::get('/account/my-orders', [AccountController::class, 'invoicesIndex'])
    //     ->name('orders.index');
    // // 2. ✅ My Invoices (NEW: Where all invoices/past orders are listed)
    // Route::get('/invoice/{order_id}', [AccountController::class, 'showInvoice'])
    //     ->name('invoice.show');
    // // 3. Invoice Show (For viewing a specific invoice PDF/HTML)
    // Route::get('/account/invoices/{order_id}', [AccountController::class, 'showInvoice'])->name('account.invoices.show');

    // // Example Route in web.php
    // Route::get('/account/invoices', [AccountController::class, 'invoicesIndex'])->name('invoices.index');

    // 1. ✅ PRIMARY LISTING ROUTE: "My Orders" will show the list of all invoices
    Route::get('/account/my-orders', [AccountController::class, 'invoicesIndex'])
        ->name('orders.index');

    // 2. Invoice Detail Route: (This is essential for the 'View Invoice' button)
    Route::get('/invoice/{order_id}', [AccountController::class, 'showInvoice'])
        ->name('invoice.show');
});

// =========================================================================================
// ✅ ALL PROTECTED ADMIN ROUTES (auth:admin Middleware)
// =========================================================================================
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {

    // 1. DASHBOARD Route Update (Data fetching ke liye)
    Route::get('/dashboard', function () {
        // Data fetch karo
        $productCount = Product::count();
        $categoryCount = Category::count();
        $userCount = User::count(); // Customer users ka count

        // Data ko view mein pass karo
        return view('admin.dashboard', compact('productCount', 'categoryCount', 'userCount'));
    })->name('admin.dashboard'); // ✅ YEH AB DATA PASS KAREGA

    // 2. CATEGORIES MANAGEMENT ...
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');

    // 3. PRODUCT MANAGEMENT ...
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');

    // USERS MANAGEMENT ROUTES ...
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');

    // Update & Delete ...
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    // 4. ORDERS MANAGEMENT (New Section)
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}/status', [OrderController::class, 'updateStatus'])
    ->name('admin.orders.updateStatus');
});
// Shop Routes (Customer Facing)
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/{slug}', [ShopController::class, 'show'])->name('show');
});
