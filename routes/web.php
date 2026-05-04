<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\Auth\PasswordController; 

/*
| 1. PUBLIC ROUTES (ZONA BEBAS)
*/

// Halaman Depan (Landing: Banner + Layanan)
Route::get('/', [CustomerController::class, 'landing'])->name('customer.landing'); 

// Halaman Menu (Hanya Makanan)
Route::get('/menu', [CustomerController::class, 'menu'])->name('menu.index'); 

// Halaman About
Route::view('/about', 'customer.about')->name('about'); 

// Halaman Kontak
Route::view('/contact', 'customer.contact')->name('contact'); 


/*
| 2. CUSTOMER ROUTES (KERANJANG & CHECKOUT)
*/

// --- Route Keranjang (Cart) ---
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.show'); 
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add'); 
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove'); 
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update'); 

// --- Route Checkout & Transaksi ---
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index'); 
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process'); 

// REMOVE DI HALAMAN CHECKOUT
Route::post('/checkout/update-cart', [CheckoutController::class, 'updateCart'])->name('checkout.update_cart'); 
Route::post('/checkout/remove-item', [CheckoutController::class, 'removeItem'])->name('checkout.remove_item'); 

// Halaman Pembayaran & Sukses
Route::get('/checkout/payment/{id}', [CheckoutController::class, 'payment'])->name('checkout.payment'); 
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success'); 

// Route Upload Bukti Bayar (Khusus QRIS)
Route::post('/checkout/upload-proof/{id}', [CheckoutController::class, 'uploadProof'])->name('checkout.upload_proof'); 

// Route Batalkan Pesanan (Customer)
Route::post('/checkout/cancel/{id}', [CheckoutController::class, 'cancelOrder'])->name('checkout.cancel'); 


// --- Route Lacak Pesanan ---
Route::get('/track', [TrackOrderController::class, 'index'])->name('track.index'); 
Route::get('/track/search', [TrackOrderController::class, 'search'])->name('track.search'); 


/*
| 3. AUTH ROUTES (LOGIN / REGISTER / PROFILE)
*/
require __DIR__.'/auth.php'; 

// [BARU] GROUP ROUTE KHUSUS USER LOGIN (PROFIL & RIWAYAT)
Route::middleware(['auth'])->group(function () {
    
    // Halaman Profil & Riwayat Pesanan
    Route::get('/my-profile', [CustomerController::class, 'profile'])->name('customer.profile'); 

    // Route bawaan Breeze untuk update profile, password & hapus akun
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); 
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); 
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update'); 

    // Route untuk upload foto profil
    Route::post('/profile/photo', [CustomerController::class, 'updateProfilePhoto'])->name('profile.photo.update'); 
});


/*
| 4. ADMIN ROUTES (ZONA TERLARANG)
*/
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Redirect dashboard bawaan ke admin dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard'); 

    // [UPDATE & BARU] ROUTE UNTUK PECAHAN DASHBOARD
    
    // 1. Dashboard Utama (Ringkasan & Grafik)
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard'); 
    
    // 2. Kelola Menu
    Route::get('/admin/menu', [AdminController::class, 'menuIndex'])->name('admin.menu.index'); 
    
    // 3. Kelola Banner & Promo
    Route::get('/admin/banner', [AdminController::class, 'bannerIndex'])->name('admin.banner.index'); 
    
    // 4. Kelola Pesanan
    Route::get('/admin/orders', [AdminController::class, 'ordersIndex'])->name('admin.orders.index'); 
    

    // CRUD Menu (POST/DELETE tetap diperlukan)
    Route::post('/admin/menu', [AdminController::class, 'store'])->name('admin.menu.store'); 
    Route::post('/admin/menu/{id}/toggle', [AdminController::class, 'toggle'])->name('admin.menu.toggle'); 
    Route::post('/admin/menu/{id}/update', [AdminController::class, 'update'])->name('admin.menu.update'); 
    Route::delete('/admin/menu/{id}', [AdminController::class, 'destroy'])->name('admin.menu.delete'); 

    // CRUD Banner
    Route::post('/admin/banner', [AdminController::class, 'storeBanner'])->name('admin.banner.store'); 
    Route::post('/admin/banner/{id}/toggle', [AdminController::class, 'toggleBanner'])->name('admin.banner.toggle'); 
    Route::delete('/admin/banner/{id}', [AdminController::class, 'destroyBanner'])->name('admin.banner.delete'); 

    // CRUD Promo
    Route::post('/admin/promo', [AdminController::class, 'storePromo'])->name('admin.promo.store'); 
    Route::get('/admin/promo/{id}/delete', [AdminController::class, 'deletePromo'])->name('admin.promo.delete'); 

    // Update Status Order (Paid, Cooking, Completed)
    Route::post('/admin/order/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.order.update_status'); 

    // Handle Pembatalan Pesanan (Admin: Setuju/Tolak)
    Route::post('/admin/order/{id}/handle-cancel', [AdminController::class, 'handleCancellation'])->name('admin.order.handle_cancel'); 
});