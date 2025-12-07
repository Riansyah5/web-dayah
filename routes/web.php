<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\DashboardController;

// Guest (Belum Login)
Route::middleware('guest')->group(function () {
	Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
	Route::post('/login', [AuthController::class, 'login'])->name('login.process');
	// Route::get('/login2', fn() => view('auth.login2'))->name('login2');
});

// Authenticated
Route::middleware('auth')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

	// KHUSUS ADMIN
	Route::middleware('role:Admin')->group(function () {
		Route::resource('/kategori', KategoriController::class);
		Route::resource('/jabatan', JabatanController::class);
		Route::resource('/pegawai', PegawaiController::class);
		// User Routes
		Route::resource('/user', UserController::class);
		Route::get('/user/create/{pegawai}', [UserController::class, 'create'])->name('tambah-akun');
		Route::post('/user/create/{pegawai}', [UserController::class, 'store'])->name('simpan-akun');
		// Route untuk update status user
		Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
	});

	// KHUSUS GURU
	Route::middleware('role:Guru')->group(function () {
		Route::get('/guru-area', function () {
			return "HALAMAN GURU";
		});
	});
});

// Route::get('/', function () {
//     return view('index');    
// })->name('dashboard');



