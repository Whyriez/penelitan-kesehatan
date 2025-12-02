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
        Route::get('/admin/dokumen-masuk', [DokumenMasukController::class, 'index'])->name('admin.dokumen_masuk');

        Route::resource('admin', AdminController::class)->names('admin');

        Route::resource('/kelola-user', \App\Http\Controllers\Admin\UserController::class)
            ->parameters(['kelola-user' => 'user'])
            ->names('admin.users')
            ->except(['show']);

        Route::resource('/jenis-izin', \App\Http\Controllers\Admin\JenisIzinController::class)
            ->parameters(['jenis-izin' => 'jenisIzin']) // Agar variabel di controller jadi $jenisIzin
            ->names('admin.jenis_izin');
    });

    //? Operator
    Route::middleware(['cekrole:operator'])->group(function () {
        Route::get('/operator/dokumen-masuk', [\App\Http\Controllers\Operator\DokumenMasukController::class, 'index'])->name('operator.dokumen_masuk');

        Route::resource('operator', \App\Http\Controllers\Operator\OperatorController::class)->names('operator');

        Route::get('/validasi-dokumen', [\App\Http\Controllers\Operator\ValidasiDokumenController::class, 'index'])->name('operator.validasi_dokumen');
        Route::patch('/validasi-dokumen/{arsip}/validasi', [\App\Http\Controllers\Operator\ValidasiDokumenController::class, 'validasi'])->name('operator.dokumen.lakukanValidasi');
        Route::patch('/validasi-dokumen/{arsip}/revisi', [\App\Http\Controllers\Operator\ValidasiDokumenController::class, 'revisi'])->name('operator.dokumen.lakukanRevisi');

        Route::get('/operator/dokumen-masuk/export', [\App\Http\Controllers\Operator\DokumenMasukController::class, 'export'])->name('operator.dokumen_masuk.export');
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
