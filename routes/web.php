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
Route::get('/admin/employees/export', [AdminController::class, 'exportEmployeesCsv'])->name('admin.employees.export');
Route::post('/admin/employees/import', [AdminController::class, 'importEmployeesCsv'])->name('admin.employees.import');

// Rute CRUD Kendaraan
Route::post('/admin/vehicles', [AdminController::class, 'storeVehicle'])->name('admin.vehicles.store');
Route::put('/admin/vehicles/{id}', [AdminController::class, 'updateVehicle'])->name('admin.vehicles.update');
Route::delete('/admin/vehicles/{id}', [AdminController::class, 'deleteVehicle'])->name('admin.vehicles.delete');

// Rute Aksi Peminjaman
Route::post('/admin/loans/{id}/approve', [AdminController::class, 'approveLoan'])->name('admin.loans.approve');
Route::post('/admin/loans/{id}/reject', [AdminController::class, 'rejectLoan'])->name('admin.loans.reject');
Route::post('/admin/loans/{id}/return', [AdminController::class, 'returnLoan'])->name('admin.loans.return');

// Rute Logout
Route::get('/logout', function () {
    return redirect()->route('login');
})->name('logout');