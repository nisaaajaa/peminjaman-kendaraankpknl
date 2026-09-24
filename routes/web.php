<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

// Rute Peminjaman Kendaraan
Route::get('/', [VehicleController::class, 'index'])->name('home');
Route::get('/pinjam/{id}', [VehicleController::class, 'create'])->name('pinjam.form');

// Tambahkan proteksi anti-spam (throttle: 5 request per menit)
Route::post('/pinjam/{id}', [VehicleController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('pinjam.store');

// Rute Form Peminjaman Langsung (Mencegah Error 404 pada URL /form)
// Dihapus karena usang dan error
Route::get('/form', fn() => redirect('/'))->name('form');

// Rute Login Admin
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Admin yang dilindungi Auth
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Rute CRUD Pegawai
    Route::post('/employees', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
    Route::put('/employees/{id}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
    Route::delete('/employees/{id}', [AdminController::class, 'deleteEmployee'])->name('admin.employees.delete');
    Route::get('/employees/export', [AdminController::class, 'exportEmployeesCsv'])->name('admin.employees.export');
    Route::post('/employees/import', [AdminController::class, 'importEmployeesCsv'])->name('admin.employees.import');
    
    // Rute CRUD Kendaraan
    Route::post('/vehicles', [AdminController::class, 'storeVehicle'])->name('admin.vehicles.store');
    Route::put('/vehicles/{id}', [AdminController::class, 'updateVehicle'])->name('admin.vehicles.update');
    Route::delete('/vehicles/{id}', [AdminController::class, 'deleteVehicle'])->name('admin.vehicles.delete');
    
    // Rute Aksi Peminjaman
    Route::post('/loans/{id}/approve', [AdminController::class, 'approveLoan'])->name('admin.loans.approve');
    Route::post('/loans/{id}/reject', [AdminController::class, 'rejectLoan'])->name('admin.loans.reject');
    Route::post('/loans/{id}/return', [AdminController::class, 'returnLoan'])->name('admin.loans.return');
});