<?php

use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');    
})->name('dashboard');

Route::resource('/kategori', KategoriController::class);
Route::resource('/jabatan', JabatanController::class);
Route::resource('/pegawai', PegawaiController::class);
// User Routes
Route::resource('/user', UserController::class);
Route::get('/user/create/{pegawai}', [UserController::class, 'create'])->name('tambah-akun');
Route::post('/user/create/{pegawai}', [UserController::class, 'store'])->name('simpan-akun');
// Route untuk update status user
Route::patch('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');





