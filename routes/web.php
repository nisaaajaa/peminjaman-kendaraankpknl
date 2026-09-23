<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AdminController;

// Rute Peminjaman Kendaraan
Route::get('/', [VehicleController::class, 'index'])->name('home');
Route::get('/pinjam/{id}', [VehicleController::class, 'create'])->name('pinjam.form');

// Tambahkan proteksi anti-spam (throttle: 5 request per menit)
Route::post('/pinjam/{id}', [VehicleController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('pinjam.store');

// Rute Form Peminjaman Langsung (Mencegah Error 404 pada URL /form)
Route::get('/form', function () {
    return view('form');
})->name('form');

// Rute Login Admin (Masih Hardcoded untuk Testing)
Route::get('/login', function () {
    return view('login');
})->name('login');

// Rute Dashboard Admin (Menggunakan Controller)
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

// Rute CRUD Pegawai
Route::post('/admin/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
Route::put('/admin/employees/{id}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
Route::delete('/admin/employees/{id}', [AdminController::class, 'deleteEmployee'])->name('admin.employees.delete');

// Rute Logout
Route::get('/logout', function () {
    return redirect()->route('login');
})->name('logout');