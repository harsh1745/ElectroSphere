<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\ForgotController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Frontend\InvoiceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\AboutController;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;


// --- DEFAULT USER AUTH & HOME ---
Route::get('/', function () {
    return redirect('/home');
});

Route::get('/home', function () {
    return view('frontend.Home.home');
})->name('home');


Auth::routes(['reset' => false, 'email' => false, 'verify' => true]);



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

    return Storage::response($path);
})->name('storage.product.show');


Route::middleware(['auth'])->group(function () {
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/wishlist/drawer-content', [WishlistController::class, 'getDrawerContent'])->name('wishlist.drawer.content');


    // 1. Full Cart Page (GET)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

    // 2. Add Item (POST AJAX)
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');

    // 3. Update Cart Quantities 
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

    // 4. Remove Item (DELETE/AJAX from Cart Page)
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');

    // 5. FIX: Instant Quantity Update Route (for AJAX)
    Route::post('/cart/update-item/{cartItem}', [CartController::class, 'updateItemQuantity'])->name('cart.update.item');

    // 6. NEW: COUPON APPLICATION ROUTE
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');

    // 7. NEW: REVIEW SUBMISSION ROUTE
    Route::post('/reviews/submit/{product}', [ReviewController::class, 'store'])->name('reviews.store');


    // 8. NEW: CHECKOUT ROUTE (Used by Buy Now action)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

    // 9. Checkout Page Display Karna (GET)
    Route::get('/checkout', [App\Http\Controllers\Frontend\CheckoutController::class, 'index'])
        ->name('checkout.index');

    // 10. Order Process Karna (POST)
    Route::post('/checkout/process', [App\Http\Controllers\Frontend\CheckoutController::class, 'process'])
        ->name('checkout.process');
    Route::post('/user/address', [AddressController::class, 'store'])->name('user.address.store');

    Route::put('/user/address/{id}', [AddressController::class, 'update'])->name('user.address.update');

    Route::delete('/user/address/{id}', [AddressController::class, 'destroy'])->name('user.address.delete');

    Route::get('/account/my-orders', [AccountController::class, 'invoicesIndex'])
        ->name('orders.index');

    Route::get('/invoice/{order_id}', [AccountController::class, 'showInvoice'])
        ->name('invoice.show');
    Route::get('/account', [App\Http\Controllers\Frontend\AccountController::class, 'index'])
        ->name('account.index');

    Route::get('/account/edit', [App\Http\Controllers\Frontend\AccountController::class, 'edit'])
        ->name('account.edit');

    Route::post('/account/update', [App\Http\Controllers\Frontend\AccountController::class, 'update'])
        ->name('account.update');
    Route::get('/account/forgot-password', [ForgotController::class, 'showForm'])->name('custom.forgot');
    Route::post('/account/forgot-password/check', [ForgotController::class, 'checkUser'])->name('custom.forgot.check');
    Route::get('/account/reset-password/{id}', [ForgotController::class, 'showResetForm'])->name('custom.reset');
    Route::post('/account/reset-password/{id}', [ForgotController::class, 'resetPassword'])->name('custom.reset.save');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');

    Route::post('/contact/send', [ContactController::class, 'store'])->name('contact.store');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
});

// =========================================================================================
// ✅ ALL PROTECTED ADMIN ROUTES (auth:admin Middleware)
// =========================================================================================
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

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

    Route::get('/admin/contacts', [ContactAdminController::class, 'index'])
        ->name('admin.contacts.index');
});
// Shop Routes (Customer Facing)
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/{slug}', [ShopController::class, 'show'])->name('show');
});
