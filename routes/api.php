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

// ✅ Auth umum
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/register', [AuthApiController::class, 'register']);
// Optional: reset password API
// Route::post('/forgot-password', ...);

Route::middleware(['auth:sanctum'])->group(function () {

    // 🔐 Authenticated User
    Route::post('/logout', [AuthApiController::class, 'logout']);
    Route::get('/user/me', [AuthApiController::class, 'me']);

    // 📄 Info umum
    Route::get('/informasi', [UserApiController::class, 'informasi']);

    // 🔁 Konsumen
    Route::apiResource('/konsumen', KonsumenApiController::class);
    Route::post('/konsumen/{id}/reset-password', [KonsumenApiController::class, 'resetPassword']);

    // 💳 Pembayaran
    Route::get('/pembayaran', [PembayaranApiController::class, 'index']);
    Route::post('/pembayaran', [PembayaranApiController::class, 'store']);
    Route::put('/pembayaran/{id}', [PembayaranApiController::class, 'update']);
    Route::delete('/pembayaran/{id}', [PembayaranApiController::class, 'destroy']);
    Route::patch('/pembayaran/{id}/status', [PembayaranApiController::class, 'updateStatus']);

    // 💰 Topup
    Route::get('/topup', [TopupApiController::class, 'index']);
    Route::patch('/topup/{id}/confirm', [TopupApiController::class, 'confirm']);
    Route::post('/topup/manual', [TopupApiController::class, 'topupManual']);
    Route::post('/topup/scan', [TopupApiController::class, 'verifikasiQR']);
    Route::get('/topup/histori', [TopupApiController::class, 'histori']);
    Route::post('/topup', [TopupApiController::class, 'storeFlutter']);

    // 🔔 Notifikasi (user & admin)
    Route::get('/notifikasi', [NotifikasiApiController::class, 'index']);

    // 👑 ADMIN ONLY
    Route::middleware(['isadmin'])->group(function () {
        Route::get('/dashboard', [DashboardApiController::class, 'index']);
        Route::get('/data-user', [UserApiController::class, 'index']);
        Route::get('/laporan-pengguna', [LaporanApiController::class, 'index']);
        Route::post('/notifikasi', [NotifikasiApiController::class, 'kirim']);
    });
});

