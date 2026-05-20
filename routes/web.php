<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;

// 1. Rute Publik (Sebelum Login)
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 2. Rute Terproteksi (Wajib Login)
Route::middleware(['auth'])->group(function () {
    
    // --- RUTE KHUSUS ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        // Beranda Admin (Kartu Statistik)
        Route::get('/home', [AdminController::class, 'home'])->name('home');
        
        // Dashboard Admin (Tabel & Filter)
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // Aksi Data Aduan
        Route::post('/aduan/update-status/{id}', [AdminController::class, 'updateStatus'])->name('aduan.updateStatus');
        Route::get('/aduan/{id}', [AdminController::class, 'show'])->name('detail_aduan');
        Route::delete('/aduan/{id}', [AdminController::class, 'destroy'])->name('aduan.destroy');
        
        // Kelola Anggota (URL: /admin/anggota | Nama Route: admin.anggota)
        Route::get('/anggota', [AdminController::class, 'anggota'])->name('anggota');
        
        // Fitur Tambah Anggota / Register yang dipindah ke dalam Admin
        Route::get('/anggota/tambah', [RegisterController::class, 'showRegister'])->name('anggota.create');
        Route::post('/anggota/tambah', [RegisterController::class, 'register'])->name('anggota.store');
        Route::delete('/admin/anggota/{id}', [AdminController::class, 'destroyAnggota'])->name('anggota.destroy');

       
    });

    // --- RUTE KHUSUS PENGHUNI (USER) ---
    Route::prefix('user')->name('user.')->group(function () {
        // Beranda Penghuni
        Route::get('/home', [UserController::class, 'home'])->name('home');
        
        // Menu Aduan
        Route::get('/aduan/create', [UserController::class, 'create'])->name('aduan.create');
        Route::get('/aduan/list', [UserController::class, 'aduanList'])->name('aduan.list');
        Route::post('/aduan/store', [UserController::class, 'store'])->name('aduan.store');
        Route::get('/aduan/{id}', [UserController::class, 'show'])->name('detail_aduan');
        
        // FAQ Penghuni
        Route::get('/faq', [UserController::class, 'faq'])->name('faq');
    });

});