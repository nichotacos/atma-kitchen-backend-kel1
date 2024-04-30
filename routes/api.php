<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BahanBakuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetailResepController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\CustomerController;
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

//Change Password Karyawan
//Route::middleware('auth:employee')->post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan']);
Route::post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan'])->middleware('auth:employee');
Route::get('/showTransaksisByCustomer', [AuthController::class, 'showTransaksisByCustomer'])->middleware('auth:api');

Route::group(['middleware' => 'auth:customer-api'], function () {
    //Auth
    Route::put('/update-profile', [CustomerController::class, 'updateProfile']);
    Route::get('/show-transaksi-customer', [CustomerController::class, 'showTransaksiCustomer']);

    //Customer
    Route::get('/customers/search/{nama}', [CustomerController::class, 'search']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/delete/{id}', [CustomerController::class, 'destroy']);
});

Route::group(['middleware' => 'auth:employee-api'], function () {
    //Change Password
    Route::post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan']);

    //Bahan Baku
    Route::get('/bahan-bakus', [BahanBakuController::class, 'index']);
    Route::post('/bahan-bakus', [BahanBakuController::class, 'store']);
    Route::put('/bahan-bakus/update/{id}', [BahanBakuController::class, 'update']);
    Route::delete('/bahan-bakus/delete/{id}', [BahanBakuController::class, 'destroy']);
    Route::get('/bahan-bakus/search/{id}', [BahanBakuController::class, 'search']);
    
    //Customer
    Route::get('/customers/search/{nama}', [CustomerController::class, 'search']);
    Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/change-password-karyawan', [AuthController::class, 'changePasswordKaryawan'])->middleware('auth:employee');
Route::get('/showTransaksisByCustomer', [AuthController::class, 'showTransaksisByCustomer'])->middleware('auth:api');

//Detail Resep
Route::get('/detail_reseps', [DetailResepController::class, 'index']);
Route::post('/detail_reseps', [DetailResepController::class, 'store']);
Route::get('/detail_reseps/{id}', [DetailResepController::class, 'show']);
Route::put('/detail_reseps/update/{id}', [DetailResepController::class, 'update']);
Route::delete('/detail_reseps/delete/{id}', [DetailResepController::class, 'delete']);

//Karyawan
Route::get('/karyawans', [KaryawanController::class, 'index']);
Route::post('/karyawans', [KaryawanController::class, 'store']);
Route::get('/karyawans/{id}', [KaryawanController::class, 'show']);
Route::put('/karyawans/update/{id}', [KaryawanController::class, 'update']);
Route::delete('/karyawans/delete/{id}', [KaryawanController::class, 'delete']);

//Gaji atau bonus
Route::get('/penggajians', [PenggajianController::class, 'index']);
Route::post('/penggajians', [PenggajianController::class, 'store']);
Route::get('/penggajians/{id}', [PenggajianController::class, 'show']);
Route::put('/penggajians/update/{id}', [PenggajianController::class, 'update']);
Route::delete('/penggajians/delete/{id}', [PenggajianController::class, 'delete']);

//Presensi
Route::get('/presensis', [PresensiController::class, 'index']);
Route::post('/presensis', [PresensiController::class, 'store']);
Route::get('/presensis/{id}', [PresensiController::class, 'show']);
Route::put('/presensis/update/{id}', [PresensiController::class, 'update']);
Route::delete('/presensis/delete/{id}', [PresensiController::class, 'delete']);

//Customer
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers', [CustomerController::class, 'store']);
Route::get('/customers/{id}', [CustomerController::class, 'show']);
Route::put('/customers/update/{id}', [CustomerController::class, 'update']);
Route::delete('/customers/delete/{id}', [CustomerController::class, 'delete']);

//Produk
Route::get('/products', [ProdukController::class, 'index']);
Route::post('/products', [ProdukController::class, 'store']);
Route::get('/products/{id}', [ProdukController::class, 'show']);
Route::put('/products/update/{id}', [ProdukController::class, 'update']);
Route::delete('/products/delete/{id}', [ProdukController::class, 'delete']);
Route::get('/products/search/{nama_produk}', [ProdukController::class, 'search']);

//Promo Poin
Route::get('/promo-poin', [PromoPoinController::class, 'index']);
Route::post('/promo-poin', [PromoPoinController::class, 'store']);
Route::get('/promo-poin/{id}', [PromoPoinController::class, 'show']);
Route::put('/promo-poin/update/{id}', [PromoPoinController::class, 'update']);
Route::delete('/promo-poin/delete/{id}', [PromoPoinController::class, 'delete']);
Route::get('/promo-poin/search/{batas_kelipatan}', [PromoPoinController::class, 'search']);

//Role
Route::get('/roles', [RoleController::class, 'index']);
Route::post('/roles', [RoleController::class, 'store']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::put('/roles/update/{id}', [RoleController::class, 'update']);
Route::delete('/roles/delete/{id}', [RoleController::class, 'delete']);
Route::get('/roles/search/{nama_role}', [RoleController::class, 'search']);

});