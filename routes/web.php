<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KategoriTransaksiController;
use App\Http\Controllers\KomentarController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ThumbnileController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Routing\RouteGroup;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, "home"])->name("home");
Route::get('/cart/{title}', [HomeController::class, "cart"])->name("cart");
Route::get('/search/merek/{search}', [HomeController::class, "home_by_merek"])->name("home_by_merek");
Route::get('/search/barang/{search}', [HomeController::class, "home_by_search"])->name("home_by_search");
Route::post('/process_search', [HomeController::class, "process_search"])->name("process_search");
Route::get('/show', [HomeController::class, "show_snap"])->name("show");
Route::get('/method_get', [HomeController::class, "method_get"])->name("method_get");
Route::get('/method_post', [HomeController::class, "method_post"])->name("method_post");
// Route::get('/validation', [AuthController::class, "validation_transaction"])->name("show");
Route::middleware(['auth:client'])->group(function () {
    Route::get('/keranjang', [HomeController::class, "keranjang"])->name("keranjang");
    Route::get('/status_transaksi', [HomeController::class, "status_transaksi"])->name("status_transaksi");
    Route::post('/process_keranjang', [HomeController::class, "process_keranjang"])->name("process_keranjang");
    Route::get('/checkout', [HomeController::class, "checkout"])->name("checkout");
    Route::post('/process_checkout', [HomeController::class, "process_checkout"])->name("process_checkout");
    Route::post('/payment', [HomeController::class, "payment"])->name("payment");
    Route::post('/payment_checkout', [HomeController::class, "payment_checkout"])->name("payment_checkout");
    Route::get('/delete_keranjang/{keranjang}', [HomeController::class, "delete_keranjang"])->name("delete_keranjang");
    Route::post('/checkouts', [HomeController::class, "pre_checkout"])->name("pre_checkout");
    Route::get('/cart_to_checkout', [HomeController::class, "cart_to_checkout"])->name("cart_to_checkout");
    Route::post('/send_comment', [HomeController::class, "send_comment"])->name("send_comment");
    Route::get('/validation_payment', [HomeController::class, "validation_payment"])->name("validation_payment");
    Route::post('/validation_checkout', [HomeController::class, "validation_checkout"])->name("validation_checkout");
});

// Admin
Route::prefix('/auth')->group(function () {
    Route::get('/login', [AuthController::class, "login_admin"])->name('auth.login');
    Route::post('/process_login', [AuthController::class, "process_login_admin"])->name('auth.process_login');
    Route::get('/forgot_password', [AuthController::class, "forgot_password_admin"])->name('auth.forgot_password');
    Route::get('/verify/{id}', [AuthController::class, "verify_admin"])->name('auth.verify');
    Route::post('/process_forgot_password', [AuthController::class, "process_forgot_password_admin"])->name('auth.process_forgot_password');
    Route::post('/process_verify', [AuthController::class, "process_verify_admin"])->name('auth.process_verify');
    Route::get('/logout', [AuthController::class, "logout_admin"])->name('auth.logout');
});

// User
Route::prefix('/user')->group(function () {
    Route::get('/login', [AuthController::class, "login_user"])->name('user.login');
    Route::post('/process_login_user', [AuthController::class, "process_login_user"])->name('user.process_login');
    Route::get('/forgot_password', [AuthController::class, "forgot_password_user"])->name('user.forgot_password');
    Route::get('/verify/{id}', [AuthController::class, "validation_user"])->name('user.verify');
    Route::get('/register', [AuthController::class, "register_user"])->name('user.register');
    Route::post('/process_register', [AuthController::class, "process_register"])->name('user.process_register');
    Route::post('/process_forgot_password', [AuthController::class, "process_forgot_password"])->name('user.process_forgot_password');
    Route::post('/process_verify', [AuthController::class, "process_verify"])->name('user.process_verify');
    Route::get('/user_logout', [AuthController::class, "logout_user"])->name('user.logout');
});

// Auth Admin
Route::middleware(['auth:admin'])->group(function () {
    Route::prefix('/staff')->group(function () {
        Route::get('/', [AdminController::class, "index"])->name('staff.view');
        Route::post('/insert', [AdminController::class, "store"])->name('staff.insert');
        Route::delete('/delete/{staff}', [AdminController::class, "destroy"])->name('staff.delete');
        // Route::post('/forgot_password', [AdminController::class, "index"])->name('staff.view');
    });
    Route::prefix('/merek')->group(function () {
        Route::get('/', [MerekController::class, "index"])->name('merek.view');
        Route::post('/insert', [MerekController::class, "store"])->name('merek.insert');
        Route::put('/update/{merek}', [MerekController::class, "update"])->name('merek.update');
        Route::delete('/delete/{merek}', [MerekController::class, "destroy"])->name('merek.delete');
    });
    Route::prefix('/barang')->group(function () {
        Route::get('/', [BarangController::class, "index"])->name('barang.view');
        Route::post('/insert', [BarangController::class, "store"])->name('barang.insert');
        Route::put('/update/{barang}', [BarangController::class, "update"])->name('barang.update');
        Route::delete('/delete/{barang}', [BarangController::class, "destroy"])->name('barang.delete');
    });
    Route::prefix('/promo')->group(function () {
        Route::get('/', [PromoController::class, "index"])->name('promo.view');
        Route::get('/insert', [PromoController::class, "store"])->name('promo.insert');
        Route::get('/update/{promo}', [PromoController::class, "update"])->name('promo.update');
        Route::get('/delete/{barang}', [PromoController::class, "destroy"])->name('promo.delete');
    });
    Route::prefix('/transaksi')->group(function () {
        Route::get('/informasi', [TransaksiController::class, "informasi_transaksi"])->name('informasi.transaksi');
        Route::get('/pengiriman', [TransaksiController::class, "informasi_pengiriman"])->name('pengiriman.transaksi');
    });
    Route::prefix('/thumbnile')->group(function () {
        Route::get('/', [ThumbnileController::class, "index"])->name('thumbnile.view');
    });
    Route::prefix('/komentar')->group(function () {
        Route::get('/', [KomentarController::class, "index"])->name('komentar.view');
        Route::post('/insert', [KomentarController::class, "store"])->name('komentar.insert');
        Route::put('/update/{komentar}', [KomentarController::class, "update"])->name('komentar.update');
        Route::delete('/delete/{komentar}', [KomentarController::class, "destroy"])->name('komentar.delete');
    });
});
