<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

// Rute Peminjaman Kendaraan
Route::get('/', [VehicleController::class, 'index'])->name('home');
Route::get('/pinjam/{id}', [VehicleController::class, 'create'])->name('pinjam.form');
Route::post('/pinjam/{id}', [VehicleController::class, 'store'])->name('pinjam.store');

// Rute Form Peminjaman Langsung (Mencegah Error 404 pada URL /form)
Route::get('/form', function () {
    return view('form');
})->name('form');

// Rute Login Admin
Route::get('/login', function () {
    return view('login');
})->name('login');

// Rute Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

// Rute Logout
Route::get('/logout', function () {
    return redirect()->route('login');
})->name('logout');