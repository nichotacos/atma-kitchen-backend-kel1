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
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\JenisKetersediaanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KemasanController;
use App\Http\Controllers\PenitipController;
use App\Http\Controllers\UkuranProdukController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\AlamatController;

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
    Route::get('/show-profile', [CustomerController::class, 'showProfile']);
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
    Route::get('/detail-reseps', [DetailResepController::class, 'index']);
    Route::post('/detail-reseps', [DetailResepController::class, 'store']);
    Route::put('/detail-reseps/update/{id}', [DetailResepController::class, 'update']);
    Route::delete('/detail-reseps/delete/{id}', [DetailResepController::class, 'destroy']);
    Route::get('/detail_reseps/search/{nama_bahan_baku}', [DetailResepController::class, 'search']);

    //Resep
    // Route::get('/reseps', [ResepController::class, 'index']);
    // Route::post('/reseps', [ResepController::class, 'store']);
    // Route::get('/reseps/{id}', [ResepController::class, 'show']);
    // Route::put('/reseps/update/{id}', [ResepController::class, 'update']);
    // Route::delete('/reseps/delete/{id}', [ResepController::class, 'destroy']);

    //Karyawan
    Route::put('/karyawans/editGajiBonus/{id}', [KaryawanController::class, 'editGajiBonus']);
    Route::get('/karyawans', [KaryawanController::class, 'index']);
    Route::post('/karyawans', [KaryawanController::class, 'store']);
    Route::put('/karyawans/update/{id}', [KaryawanController::class, 'update']);
    Route::delete('/karyawans/delete/{id}', [KaryawanController::class, 'destroy']);
    Route::get('/karyawans/search/{nama_karyawan}', [KaryawanController::class, 'search']);

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
    Route::put('/roles/update/{id}', [RoleController::class, 'update']);
    Route::delete('/roles/delete/{id}', [RoleController::class, 'destroy']);

    // Hampers Admin
    Route::post('/hampers', [HampersController::class, 'storeHampers']);
    Route::post('/hampers/add-product', [HampersController::class, 'storeProducts']);
    Route::put('/hampers/update/{id}', [HampersController::class, 'update']);
    // Buat delete hampers dan produk yang menyangkut padanya
    Route::delete('/hampers/delete/{idHampers}', [HampersController::class, 'destroy']);
    // Buat delete 1 produk di hampers tertentu
    Route::delete('hampers/deleteProduct/', [HampersController::class, 'destroyCertain']);

    // Produk Admin
    Route::post('/products', [ProdukController::class, 'store']);
    Route::put('/products/update/{id}', [ProdukController::class, 'update']);
    Route::delete('/products/delete/{id}', [ProdukController::class, 'destroy']);

    //Unit
    Route::get('/units', [UnitController::class, 'index']);

    //Jenis Ketersediaan
    Route::get('/jenis-ketersediaans', [JenisKetersediaanController::class, 'index']);
    Route::post('/jenis-ketersediaans', [JenisKetersediaanController::class, 'store']);
    Route::put('/jenis-ketersediaans/update/{id}', [JenisKetersediaanController::class, 'update']);
    Route::delete('/jenis-ketersediaans/delete/{id}', [JenisKetersediaanController::class, 'destroy']);

    //Kategori
    Route::get('/kategoris', [KategoriController::class, 'index']);
    Route::post('/kategoris', [KategoriController::class, 'store']);
    Route::put('/kategoris/update/{id}', [KategoriController::class, 'update']);
    Route::delete('/kategoris/delete/{id}', [KategoriController::class, 'destroy']);

    //Ukuran 
    Route::get('/ukurans', [UkuranProdukController::class, 'index']);
    Route::post('/ukurans', [UkuranProdukController::class, 'store']);
    Route::put('/ukurans/update/{id}', [UkuranProdukController::class, 'update']);
    Route::delete('/ukurans/delete/{id}', [UkuranProdukController::class, 'destroy']);

    //Penitip
    Route::get('/penitips', [PenitipController::class, 'index']);

    //Kemasan
    Route::get('/kemasans', [KemasanController::class, 'index']);
});

//Alamat
Route::get('/alamats', [AlamatController::class, 'index']);

//Statuses
Route::get('/statuses', [StatusController::class, 'index']);

//Produk
Route::get('/products', [ProdukController::class, 'index']);

//Hampers
Route::get('/hampers', [HampersController::class, 'index']);
