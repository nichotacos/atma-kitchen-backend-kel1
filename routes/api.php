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
use App\Http\Controllers\ResepController;

//Register Customer
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register-karyawan', [AuthController::class, 'registerKaryawan']);
Route::post('/login-customer', [AuthController::class, 'loginCustomer']);
Route::post('/login-karyawan', [AuthController::class, 'loginKaryawan'])->name('login');

//test
//Change Password Karyawan
//Route::middleware('auth:employee')->post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan']);
Route::post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan'])->middleware('auth:employee');

//Detail Resep
Route::get('/detail-reseps', [DetailResepController::class, 'index']);
Route::post('/detail-reseps', [DetailResepController::class, 'store']);
Route::put('/detail-reseps/update/{id}', [DetailResepController::class, 'update']);
Route::delete('/detail-reseps/delete/{id}', [DetailResepController::class, 'destroy']);
Route::get('/detail_reseps/search/{nama_bahan_baku}', [DetailResepController::class, 'search']);

//Resep
Route::get('/reseps', [ResepController::class, 'index']);
Route::post('/reseps', [ResepController::class, 'store']);
Route::get('/reseps/{id}', [ResepController::class, 'show']);
Route::put('/reseps/update/{id}', [ResepController::class, 'update']);
Route::delete('/reseps/delete/{id}', [ResepController::class, 'destroy']);

//Karyawan
Route::get('/karyawans', [KaryawanController::class, 'index']);
Route::post('/karyawans', [KaryawanController::class, 'store']);
Route::put('/karyawans/update/{id}', [KaryawanController::class, 'update']);
Route::delete('/karyawans/delete/{id}', [KaryawanController::class, 'destroy']);
Route::get('/karyawans/search/{nama_karyawan}', [KaryawanController::class, 'search']);

//Penggajian
Route::get('/penggajians', [PenggajianController::class, 'index']);
Route::post('/penggajians', [PenggajianController::class, 'store']);
Route::put('/penggajians/update/{id}', [PenggajianController::class, 'update']);
Route::delete('/penggajians/delete/{id}', [PenggajianController::class, 'destroy']);
Route::get('/penggajians/search/{nama_karyawan}', [PenggajianController::class, 'search']);

//Presensi
Route::get('/presensis', [PresensiController::class, 'index']);
Route::post('/presensis', [PresensiController::class, 'store']);
Route::put('/presensis/update/{id}', [PresensiController::class, 'update']);
Route::delete('/presensis/delete/{id}', [PresensiController::class, 'destroy']);
Route::get('/presensis/search/{nama_karyawan}', [PresensiController::class, 'search']);

//Customer
Route::get('/customers/showTransaksisByCustomer', [CustomerController::class, 'showTransaksisByCustomer'])->middleware('auth:api');
Route::get('/customers/showTransaksisByCustomer/{nama_produk}', [CustomerController::class, 'searchTransaksisCustomerByProduct'])->middleware('auth:api');
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/delete/{id}', [CustomerController::class, 'destroy']);
Route::get('/customers/search/{nama}', [CustomerController::class, 'search']);

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
