<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetailResepController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\CustomerController;


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

