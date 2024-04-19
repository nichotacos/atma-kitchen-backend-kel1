<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetailResepController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HampersController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PromoPoinController;
use App\Http\Controllers\RoleController;
use App\Models\ProdukHampers;

//Register Customer
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-karyawan', [AuthController::class, 'registerKaryawan']);
Route::post('/login-customer', [AuthController::class, 'loginCustomer']);
Route::post('/login-karyawan', [AuthController::class, 'loginKaryawan'])->name('login');

//Change Password Karyawan
//Route::middleware('auth:employee')->post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan']);
Route::post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan'])->middleware('auth:employee');
Route::get('/showTransaksisByCustomer', [AuthController::class, 'showTransaksisByCustomer'])->middleware('auth:api');

//Detail Resep
Route::get('/detail-reseps', [DetailResepController::class, 'index']);
Route::post('/detail-reseps', [DetailResepController::class, 'store']);
Route::put('/detail-reseps/update/{id}', [DetailResepController::class, 'update']);
Route::delete('/detail-reseps/delete/{id}', [DetailResepController::class, 'destroy']);

//Karyawan
Route::get('/karyawans', [KaryawanController::class, 'index']);
Route::post('/karyawans', [KaryawanController::class, 'store']);
Route::put('/karyawans/update/{id}', [KaryawanController::class, 'update']);
Route::delete('/karyawans/delete/{id}', [KaryawanController::class, 'destroy']);

//Gaji atau bonus
Route::get('/penggajians', [PenggajianController::class, 'index']);
Route::post('/penggajians', [PenggajianController::class, 'store']);
Route::put('/penggajians/update/{id}', [PenggajianController::class, 'update']);
Route::delete('/penggajians/delete/{id}', [PenggajianController::class, 'destroy']);

//Presensi
Route::get('/presensis', [PresensiController::class, 'index']);
Route::post('/presensis', [PresensiController::class, 'store']);
Route::put('/presensis/update/{id}', [PresensiController::class, 'update']);
Route::delete('/presensis/delete/{id}', [PresensiController::class, 'destroy']);

//Customer
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/delete/{id}', [CustomerController::class, 'destroy']);

//Produk
Route::get('/products', [ProdukController::class, 'index']);
Route::post('/products', [ProdukController::class, 'store']);
Route::put('/products/update/{id}', [ProdukController::class, 'update']);
Route::delete('/products/delete/{id}', [ProdukController::class, 'destroy']);

//Promo Poin
Route::get('/promo-poin', [PromoPoinController::class, 'index']);
Route::post('/promo-poin', [PromoPoinController::class, 'store']);
Route::put('/promo-poin/update/{id}', [PromoPoinController::class, 'update']);
Route::delete('/promo-poin/delete/{id}', [PromoPoinController::class, 'destroy']);

//Role
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/update/{id}', [RoleController::class, 'update']);
Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy']);

//Hampers
Route::get('/hampers', [HampersController::class, 'index']);
Route::post('/hampers', [HampersController::class, 'store']);
//update hampers disini
// Buat delete hampers dan produk yang menyangkut padanya
Route::delete('/hampers/delete/{idHampers}', [HampersController::class, 'destroy']);
// Buat delete 1 produk di hampers tertentu
Route::delete('hampers/deleteProduct/', [HampersController::class, 'destroyCertain']);
