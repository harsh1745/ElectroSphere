<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Auth\LoginController; // ✅ YEH LINE ADD KARO
use App\Http\Controllers\Frontend\ShopController; // Ensure this is the correct path
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

// --- IMAGE STREAMING ROUTE (FIXED LOGIC) ---
// --- IMAGE STREAMING ROUTE (FINAL ATTEMPT: STORAGE LINK PATH) ---
// Route::get('/storage/products/{filename}', function ($filename) {

//     // 1. Filename se sirf file ka naam nikalo.
//     $justFilename = basename($filename);

//     // 2. Normalized path for lookup: products/filename.png
//     // Yehi woh path hai jise aapka ProductController store kar raha hai.
//     $normalizedFilename = 'products/' . $justFilename;

//     // 3. File ko Standard Public Path mein dhoondho
//     $finalPath = 'public/' . $normalizedFilename;

//     if (Storage::exists($finalPath)) {
//         // File mil gayi. response()->file() security header ko bypass karta hai.
//         return response()->file(Storage::path($finalPath));
//     }

//     // File nahi mili, 404 de do
//     abort(404);
// })->name('storage.product.show');




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
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
});

// Shop Routes (Customer Facing)
// Shop Routes (Customer Facing)
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/{slug}', [ShopController::class, 'show'])->name('show');
});

// Cart routes ko ek alag group mein rakho
// Route::prefix('cart')->group(function () {
//     // Ye line ensure karegi ki sirf logged-in users hi 'store' action kar payenge
//     Route::post('/add/{product}', [CartController::class, 'store'])
//         ->middleware('auth') // ✅ YEH ZAROORI HAI!
//         ->name('cart.add');

//     // ... baaki cart routes ...
// });






















// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController;
// use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\ForgotPasswordController;
// use App\Http\Controllers\Auth\ResetPasswordController;
// use App\Http\Controllers\Admin\LoginController as AdminLoginController; // Admin Controller ko alias diya
// use App\Http\Controllers\Admin\CategoryController; // Naya Controller import karo
// use Illuminate\Support\Facades\Storage; // Top par import karo
// use App\Models\User;

// /*
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Hum default password reset routes ko disable karke custom DOB flow use kar rahe hain.
// |
// */

// Route::get('/', function () {
//     return redirect('/login');
// });

// // routes/web.php
// Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');

// // Sirf login, register, aur verify routes rakhein. Password reset ko disable karein.
// // 'reset' => false
// // 'email' => false (Kyuki hum email verification bhi custom bana rahe hain)
// Auth::routes(['reset' => false, 'email' => false]);

// Route::middleware(['auth'])->group(function () {
//     Route::get('/home', function () {
//         return view('home');
//     })->name('home');
// });

// // --- Custom Password Reset Flow Routes ---

// // 1. Initial Form: (GET /password/reset) - Form dikhane ke liye
// Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
//     ->name('password.request');

// // 2. Verification Submit: (POST /password/email) - DOB check karne ke liye
// // Hum default route name 'password.email' ko use kar rahe hain
// Route::post('password/email', [ForgotPasswordController::class, 'verifyDobAndRedirect'])
//     ->name('password.email');

// // 3. Reset Form Display: (GET /reset-password-custom/{email}) - New password set karne ka page
// Route::get('reset-password-custom/{email}', [ResetPasswordController::class, 'showResetFormCustom'])
//     ->name('password.reset_custom');

// // 4. Password Update: (POST /password/reset) - Final password update ki logic
// // **IMPORTANT:** Hum default URI aur route name 'password.update' ko apne custom method par point kar rahe hain.
// Route::post('password/reset', [ResetPasswordController::class, 'resetPasswordCustom'])
//     ->name('password.update');


// // --- CUSTOM ADMIN AUTH ROUTES ---
// // Admin Login Routes
// Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
// Route::post('/admin/login', [AdminLoginController::class, 'login']);
// Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


// // Admin Dashboard (Protected by 'auth:admin' guard)
// Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
//     Route::get('/dashboard', function () {
//         // You must create this file: resources/views/admin/dashboard.blade.php
//         return view('admin.dashboard');
//     })->name('admin.dashboard');
// });

// // Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
// // Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');

// // Admin Dashboard block ke andar ya alag se banao
// Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
//     // ... categories routes ...

//     // Product Routes:
//     Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index'); // <-- YEH LINE ADD KARO!
//     Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
//     Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');

//     // Product Update Routes
//     // 1. EDIT Form ko dikhana: /admin/products/{product}/edit
//     Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');

//     // 2. Data database mein update karna: (PUT/PATCH method use hoga)
//     Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');

//     // 2. Data database mein delete karna:
//     Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
// });

// Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');

// // Image ko serve karne ke liye naya route
// Route::get('/storage/products/{filename}', function ($filename) {
//     // Check karo ki file 'storage/app/public/products' mein exist karti hai
//     $path = 'public/products/' . $filename;

//     if (!Storage::exists($path)) {
//         abort(404);
//     }
    
//     // File ko browser mein stream karo
//     return Storage::response($path);
// })->name('storage.product.show');




// ----------------------comment code -----------
// use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\ForgotPasswordController;
// use App\Http\Controllers\Auth\ResetPasswordController;
// use App\Models\User; // User model ki zarurat padegi


// Route::get('/', function () {
//     return redirect('/login');
// });

// Auth::routes();
// Route::middleware(['auth'])->group(function () {
//     Route::get('/home', function () {
//         return view('home');
//     })->name('home');
// });



// // Step 1: Verification Form Submission (Email aur DOB)
// Route::post('password-verify-dob', [ForgotPasswordController::class, 'verifyDobAndRedirect'])
//     ->name('password.verify_dob');

// // Step 2: Custom Reset Form Display (Verification successful hone ke baad)
// // Hum token ki jagah sirf email bhej rahe hain URL mein.
// Route::get('reset-password-custom/{email}', [ResetPasswordController::class, 'showResetFormCustom'])
//     ->name('password.reset_custom');

// // Step 3: Password Update Logic
// Route::post('reset-password-custom', [ResetPasswordController::class, 'resetPasswordCustom'])
//     ->name('password.update_custom');

// // Baaki sab default routes jaise login, register, etc. rehne de.
    













// // NEW: Custom route for DOB verification
//     Route::post('password-verify-dob', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'verifyDobAndRedirect'])
//         ->name('password.verify_dob');
    
//     // Default password reset form ko bhi thoda modify karna padega
//     Route::get('reset-password-custom/{email}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetFormCustom'])
//         ->name('password.reset_custom');
