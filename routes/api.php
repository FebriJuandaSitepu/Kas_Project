<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\{
    AuthApiController,
    DashboardApiController,
    UserApiController,
    KonsumenApiController,
    PembayaranApiController,
    LapanganApiController,
    LaporanApiController,
    PemesananApiController,
    TopupApiController,
    NotifikasiApiController
};

// ✅ Register & Login tanpa token
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// 🔐 Semua route di bawah ini dilindungi oleh Sanctum
Route::middleware(['auth:sanctum'])->group(function () {

    // 🔒 Logout & Info user
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user/me', [AuthApiController::class, 'me']);

    // 📋 Informasi umum
    Route::get('/informasi', [UserApiController::class, 'informasi']);

    // 👑 ADMIN-ONLY ROUTES (validasi role sebaiknya di controller)
    Route::get('/dashboard', [DashboardApiController::class, 'index']);
    Route::get('/data-user', [UserApiController::class, 'index']);

    // 🔁 KONSUMEN
    Route::apiResource('/konsumen', KonsumenApiController::class);
    Route::post('/konsumen/{id}/reset-password', [KonsumenApiController::class, 'resetPassword']);

    // 💸 PEMBAYARAN
    Route::get('/pembayaran', [PembayaranApiController::class, 'index']);
    Route::post('/pembayaran', [PembayaranApiController::class, 'store']);
    Route::put('/pembayaran/{id}', [PembayaranApiController::class, 'update']);
    Route::delete('/pembayaran/{id}', [PembayaranApiController::class, 'destroy']);
    Route::patch('/pembayaran/{id}/status', [PembayaranApiController::class, 'updateStatus']);

    // 📊 LAPORAN
    Route::get('/laporan-pengguna', [LaporanApiController::class, 'index']);

    // 💰 TOP UP
    Route::get('/topup', [TopupApiController::class, 'index']);
    Route::patch('/topup/{id}/confirm', [TopupApiController::class, 'confirm']);
    Route::post('/admin/topup/manual', [TopupApiController::class, 'topupManual']);
    Route::post('/admin/topup/scan', [TopupApiController::class, 'verifikasiQR']);
    Route::get('/admin/topup/histori', [TopupApiController::class, 'histori']);

    // 🔔 NOTIFIKASI
    Route::get('/notifikasi', [NotifikasiApiController::class, 'index']);
    Route::post('/notifikasi', [NotifikasiApiController::class, 'kirim']);
});
