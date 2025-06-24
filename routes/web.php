<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    DashboardController,
    KonsumenController,
    LapanganController,
    LaporanController,
    PemesananController,
    TopupController,
    PembayaranController,
    Auth\RegisterController,
    NotifikasiController,
    Api\TopupApiController
};
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// =======================
// AUTHENTIKASI
// =======================
Route::get('/', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/', [UserController::class, 'login'])->name('auth.login');
Route::get('/forgot-password', [UserController::class, 'ShowPass'])->name('ShowPass');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// =======================
// RUTE UNTUK USER YANG LOGIN
// =======================
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');

    // HALAMAN INFORMASI (UMUM)
    Route::get('/informasi', [UserController::class, 'showInformasi'])->name('informasi');
});

// =======================
// RUTE KHUSUS ADMIN (auth + isadmin)
// =======================
Route::middleware(['auth', 'isadmin'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // DATA USER
    Route::get('/data-user', [UserController::class, 'index'])->name('user.index');

    // DATA KONSUMEN
    Route::resource('konsumen', KonsumenController::class);
    Route::post('/konsumen/{id}/reset-password', [KonsumenController::class, 'resetPassword'])->name('konsumen.resetPassword');

    // DATA PEMBAYARAN & TRANSAKSI KAS
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', [PembayaranController::class, 'index'])->name('index');
        Route::get('/create', [PembayaranController::class, 'create'])->name('create');
        Route::get('/pemasukan/create', [PembayaranController::class, 'createPemasukan'])->name('pemasukan.create');
        Route::get('/pengeluaran/create', [PembayaranController::class, 'createPengeluaran'])->name('pengeluaran.create');
        Route::post('/', [PembayaranController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PembayaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PembayaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [PembayaranController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/status', [PembayaranController::class, 'updateStatus'])->name('status');
    });

    // DATA LAPANGAN
    Route::get('/lapangan', [LapanganController::class, 'index'])->name('lapangan.index');

    // LAPORAN
    Route::get('/laporan-pengguna', [LaporanController::class, 'index'])->name('laporan.index');

    // PEMESANAN
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('pemesanan');

    // TOP UP
    Route::get('/topup', [TopupController::class, 'index'])->name('topup');
    Route::patch('/topup/{id}/confirm', [TopupApiController::class, 'confirm'])->name('topup.confirm');
    Route::post('/admin/topup/scan', [TopupController::class, 'verifikasiQR'])->name('admin.topup.verifikasiQR');
    Route::get('/admin/topup/histori', [TopupController::class, 'histori'])->name('admin.topup.histori');
    Route::post('/admin/topup/manual', [TopupController::class, 'topupManual'])->name('admin.topup.manual');

    // NOTIFIKASI
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/kirim', [NotifikasiController::class, 'kirim'])->name('kirim');
    });

    // FORM KIRIM NOTIFIKASI
    Route::get('/notifikasi/kirim', function () {
        $users = \App\Models\User::all();
        return view('notifikasi.kirim', compact('users'));
    })->name('notifikasi.kirim.form');
});
