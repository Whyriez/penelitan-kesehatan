<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DokumenMasukController;
use App\Http\Controllers\Admin\ValidasiDokumenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register_process'])->name('register.process');
Route::post('/login', [AuthController::class, 'login_process'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    //? Admin
    Route::middleware(['cekrole:admin'])->group(function () {
        Route::resource('admin', AdminController::class)->names('admin');
        Route::get('/dokumen-masuk', [DokumenMasukController::class, 'index'])->name('admin.dokumen_masuk');

        Route::get('/validasi-dokumen', [ValidasiDokumenController::class, 'index'])->name('admin.validasi_dokumen');
        Route::patch('/validasi-dokumen/{arsip}/validasi', [ValidasiDokumenController::class, 'validasi'])->name('admin.dokumen.lakukanValidasi');
        Route::patch('/validasi-dokumen/{arsip}/revisi', [ValidasiDokumenController::class, 'revisi'])->name('admin.dokumen.lakukanRevisi');

        Route::resource('/kelola-user', \App\Http\Controllers\Admin\UserController::class)
            ->parameters(['kelola-user' => 'user'])
            ->names('admin.users')
            ->except(['show']);
    });

    //? User
    Route::middleware(['cekrole:user'])->group(function () {
        // Halaman Utama User
        Route::get('/user/dashboard', [App\Http\Controllers\User\UserController::class, 'index'])->name('user.index');

        // Upload Dokumen
        Route::get('/upload-dokumen', [App\Http\Controllers\User\DokumenController::class, 'indexUpload'])->name('user.upload');
        Route::post('/upload-dokumen', [App\Http\Controllers\User\DokumenController::class, 'storeUpload'])->name('user.upload.store');

        // Riwayat Dokumen (Halaman Utama)
        Route::get('/riwayat-dokumen', [App\Http\Controllers\User\UserController::class, 'indexRiwayat'])->name('user.riwayat');

        Route::get('/dokumen/{arsip}/edit', [App\Http\Controllers\User\DokumenController::class, 'edit'])->name('user.dokumen.edit');
        Route::patch('/dokumen/{arsip}', [App\Http\Controllers\User\DokumenController::class, 'update'])->name('user.dokumen.update');
    });

    Route::get('/profile-saya', [ProfileController::class, 'index'])->name('profile');
    Route::patch('/profile-saya', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile-saya/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});
