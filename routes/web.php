<?php

use Illuminate\Support\Facades\Route;

// Public & Customer Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

// Owner Controllers
use App\Http\Controllers\owner\DashboardController as OwnerDashboard;
use App\Http\Controllers\owner\RestaurantController as OwnerRestaurant;
use App\Http\Controllers\owner\ProductController as OwnerProduct;
use App\Http\Controllers\owner\OrderController as OwnerOrder;

// Admin Controllers
use App\Http\Controllers\admin\DashboardController as AdminDashboard;
use App\Http\Controllers\admin\UserController as AdminUser;
use App\Http\Controllers\RestaurantController as AdminRestaurant;
use App\Http\Controllers\CategoryController as AdminCategory;
use App\Http\Controllers\admin\OrderController as AdminOrder;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Pemesanan Makanan
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. PUBLIC ROUTES (Dapat diakses tanpa login)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/restaurants', [HomeController::class, 'restaurants'])->name('restaurants');
Route::get('/restaurants/{restaurant}', [HomeController::class, 'showRestaurant'])->name('restaurants.show');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [HomeController::class, 'showProduct'])->name('products.show');

// Gateway Payment Webhook (Midtrans / QRIS)
Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook'])->name('payment.webhook');

// =========================================================================
// 2. AUTHENTICATION ROUTES (Tamu & Pengguna Terdaftar)
// =========================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// =========================================================================
// 3. CUSTOMER ROUTES (Khusus Pelanggan / Pembeli Makanan)
// =========================================================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'customerDashboard'])->name('dashboard');

    // Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Pemesanan & Checkout (Orders)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/payment', [OrderController::class, 'uploadPayment'])->name('orders.payment');

    // Ulasan / Rating Makanan & Restoran (Reviews)
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// =========================================================================
// 4. RESTAURANT OWNER ROUTES (Khusus Mitra Pemilik Restoran)
// =========================================================================
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboard::class, 'index'])->name('dashboard');

    // Profil & Pengaturan Restoran
    Route::get('/restaurant', [OwnerRestaurant::class, 'index'])->name('restaurant.index');
    Route::get('/restaurant/create', [OwnerRestaurant::class, 'create'])->name('restaurant.create');
    Route::post('/restaurant', [OwnerRestaurant::class, 'store'])->name('restaurant.store');
    Route::get('/restaurant/{restaurant}/edit', [OwnerRestaurant::class, 'edit'])->name('restaurant.edit');
    Route::put('/restaurant/{restaurant}', [OwnerRestaurant::class, 'update'])->name('restaurant.update');

    // Manajemen Menu Makanan (Products)
    Route::get('/products', [OwnerProduct::class, 'index'])->name('products.index');
    Route::get('/products/create', [OwnerProduct::class, 'create'])->name('products.create');
    Route::post('/products', [OwnerProduct::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [OwnerProduct::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [OwnerProduct::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [OwnerProduct::class, 'destroy'])->name('products.destroy');

    // Manajemen Pesanan Masuk (Orders)
    Route::get('/orders', [OwnerOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OwnerOrder::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OwnerOrder::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/verify-payment', [PaymentController::class, 'verifyPayment'])->name('orders.verifyPayment');
});

// =========================================================================
// 5. ADMIN ROUTES (Khusus Administrator Platform)
// =========================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    // Manajemen Pengguna (Users)
    Route::resource('/users', AdminUser::class);

    // Manajemen Mitra Restoran (Restaurants)
    Route::resource('/restaurants', AdminRestaurant::class);

    // Manajemen Kategori Makanan (Categories)
    Route::resource('/categories', AdminCategory::class);

    // Monitoring Seluruh Pesanan (Orders)
    Route::get('/orders', [AdminOrder::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrder::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrder::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/verify-payment', [PaymentController::class, 'verifyPayment'])->name('orders.verifyPayment');
});
