<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;

class AuthController extends Authenticatable
{
    // Register Customer
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
            'nomor_telepon' => ['required', 'regex:/^08\d{9,11}$/', 'unique:customers'],
            'tanggal_lahir' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $customer = Customer::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_registrasi' => now(),
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0
        ]);

        // $tokenResult = $customer->createToken('LaravelPersonalAccessToken')->plainTextToken;
        // $plainTextToken = $tokenResult->accessToken;

        return response([
            'message' => 'Berhasil registrasi akun customer.',
            'customers' => $customer
        ], 201);
    }
}
