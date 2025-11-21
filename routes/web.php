<?php

use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');    
})->name('dashboard');

Route::resource('/kategori', KategoriController::class);
Route::resource('/jabatan', JabatanController::class);
Route::resource('/pegawai', PegawaiController::class);

