<?php

// routes/web.php
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
    Route::get('/informasi', [UserController::class, 'showInformasi'])->name('informasi');
});

// =======================
// RUTE KHUSUS ADMIN (auth + isadmin)
// =======================
Route::middleware(['auth', 'isadmin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/data-user', [UserController::class, 'index'])->name('user.index');

    // KONSUMEN
    Route::resource('konsumen', KonsumenController::class);
    Route::post('/konsumen/{id}/reset-password', [KonsumenController::class, 'resetPassword'])->name('konsumen.resetPassword');

   Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
    Route::get('/', [PembayaranController::class, 'index'])->name('index');

    // Menggabungkan route create dengan parameter opsional {tipe?}
    Route::get('/create/{tipe?}', [PembayaranController::class, 'create'])->name('create');

    Route::post('/', [PembayaranController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [PembayaranController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PembayaranController::class, 'update'])->name('update');
    Route::delete('/{id}', [PembayaranController::class, 'destroy'])->name('destroy');
    Route::patch('/{id}/status', [PembayaranController::class, 'updateStatus'])->name('status');
});


    // TOPUP
    Route::get('/admin/topup', [TopupController::class, 'index'])->name('admin.topup');
    Route::get('/admin/topup/histori', [TopupController::class, 'histori'])->name('admin.topup.histori');
    Route::post('/admin/topup/manual', [TopupController::class, 'topupManual'])->name('admin.topup.manual');
    Route::post('/admin/topup/scan', [TopupController::class, 'verifikasiQR'])->name('admin.topup.verifikasiQR');
    Route::patch('/admin/topup/{id}/confirm', [TopupController::class, 'konfirmasi'])->name('admin.topup.konfirmasi');
    Route::get('/admin/topup/{id}', [TopupController::class, 'show'])->name('topup.show');

    // Form manual topup digabung di halaman index
   Route::post('/admin/topup/create-manual', [TopupController::class, 'topupManual'])->name('admin.topup.create');

    // NOTIFIKASI
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/', [NotifikasiController::class, 'index'])->name('index');
        Route::post('/kirim', [NotifikasiController::class, 'kirim'])->name('kirim');
    });
    // LAPORAN
    Route::get('/laporan/konsumen', [LaporanController::class, 'laporanKonsumen'])->name('laporan.index');



    Route::get('/notifikasi/kirim', function () {
        $users = \App\Models\User::all();
        return view('notifikasi.kirim', compact('users'));
    })->name('notifikasi.kirim.form');
});
