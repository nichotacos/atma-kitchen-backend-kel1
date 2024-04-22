<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BahanBakuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name(('password.request'));

Route::post('/forgot-password', function (Request $request) {
    // Handle POST request logic here
    return response('POST request received');
  })->middleware('guest')->name('password.email');

  Route::post('/', function () {
    // Handle POST request logic here
    return response('POST request received');
  });

  Route::get('bahanbaku',[BahanBakuController::class, 'index'])->name('pegawai');