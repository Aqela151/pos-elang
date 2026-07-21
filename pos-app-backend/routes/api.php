<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SettingsController;


use App\Http\Controllers\Api\LoginController;

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('admin.only')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);

        Route::post('/settings', [SettingsController::class, 'update']);
        Route::put('/settings', [SettingsController::class, 'update']);

        Route::apiResource('cabang', CabangController::class)->except(['index', 'show']);
        Route::apiResource('produk', ProdukController::class)->except(['index', 'show']);
        Route::apiResource('member', MemberController::class)->except(['index', 'show']);
        Route::apiResource('supplier', SupplierController::class)->except(['index', 'show']);
        Route::apiResource('users', UserController::class)->except(['index', 'show']);
        Route::apiResource('transaksi', TransaksiController::class)->except(['index', 'show', 'histori']);
        Route::apiResource('detail-transaksi', DetailTransaksiController::class)->except(['index', 'show']);
        Route::apiResource('kategori', KategoriController::class)->except(['index', 'show']);
        Route::apiResource('stok', StokController::class)->except(['index', 'show']);
    });
});

// ==== AUTH ROUTES ====
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    // ==== SETTINGS ROUTES ====
    Route::get('/settings', [SettingsController::class, 'index']);
});

// ==== ROUTES ANDA YANG SUDAH ADA (tidak diubah) ====
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('cabang', CabangController::class)->only(['index', 'show']);
    Route::apiResource('produk', ProdukController::class)->only(['index', 'show']);
    Route::apiResource('member', MemberController::class)->only(['index', 'show']);
    Route::apiResource('supplier', SupplierController::class)->only(['index', 'show']);
    Route::apiResource('users', UserController::class)->only(['index', 'show']);

    Route::get('/transaksi/histori', [TransaksiController::class, 'histori']);
    Route::apiResource('transaksi', TransaksiController::class)->only(['index', 'show', 'store']);

    Route::apiResource('detail-transaksi', DetailTransaksiController::class)->only(['index', 'show']);
    Route::apiResource('kategori', KategoriController::class)->only(['index', 'show']);
    Route::get('/stok/kasir', [StokController::class, 'kasir']);
    Route::apiResource('stok', StokController::class)->only(['index', 'show']);
});