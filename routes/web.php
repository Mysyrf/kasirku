<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Guest routes (belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Auth routes (sudah login)
Route::middleware(['auth.custom'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/', function () {
        return redirect()->route('cashier');
    });
    
    Route::get('/cashier', [CashierController::class, 'index'])->name('cashier');
    Route::post('/cart/add', [CashierController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CashierController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{productId}', [CashierController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [CashierController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart', [CashierController::class, 'getCart'])->name('cart.get');
    Route::post('/transaction/process', [CashierController::class, 'processTransaction'])->name('transaction.process');
    Route::get('/product/by-barcode/{barcode}', [CashierController::class, 'getProductByBarcode']);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{transaction}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{transaction}/print', [ReportController::class, 'print'])->name('reports.print');

    // Only Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('/products/price/manage', [ProductController::class, 'priceManagement'])->name('products.price');
        Route::get('/products/price/history', [ProductController::class, 'priceHistoryIndex'])->name('products.priceHistory');
        Route::put('/products/{product}/price', [ProductController::class, 'updatePrice'])->name('products.updatePrice');
        Route::resource('purchases', PurchaseController::class);
    });

    // Dashboard (semua user bisa akses setelah login)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});