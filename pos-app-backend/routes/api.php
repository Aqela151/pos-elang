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

// ==== AUTH ROUTES ====
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    // ==== SETTINGS ROUTES ====
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::put('/settings', [SettingsController::class, 'update']);
    Route::post('/settings', [SettingsController::class, 'update']);
});

// ==== ROUTES ANDA YANG SUDAH ADA (tidak diubah) ====
Route::apiResource('cabang', CabangController::class);
Route::apiResource('produk', ProdukController::class);
Route::apiResource('member', MemberController::class);
Route::apiResource('supplier', SupplierController::class);
Route::apiResource('users', UserController::class);

Route::get('/transaksi/histori', [TransaksiController::class, 'histori']);
Route::apiResource('transaksi', TransaksiController::class);

Route::apiResource('detail-transaksi', DetailTransaksiController::class);
Route::apiResource('kategori', KategoriController::class);
Route::apiResource('stok', StokController::class);