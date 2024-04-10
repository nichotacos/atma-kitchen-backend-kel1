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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HasApiTokens, Notifiable, HasFactory;

    // Register Customer
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:customers',
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
            'password' => Hash::make($request->password),
            'nomor_telepon' => $request->nomor_telepon,
            'tanggal_registrasi' => now(),
            'tanggal_lahir' => $request->tanggal_lahir,
            'poin' => 0,
            'saldo' => 0
        ]);

        return response([
            'message' => 'Berhasil registrasi akun customer.',
            'customers' => $customer
        ], 201);
    }

    public function loginCustomer(Request $request)
    {
        $loginData = $request->all();

        $validator = Validator::make($loginData, [
            'username' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        if (!Auth::attempt($loginData)) {
            return response([
                'message' => 'Data yang diinputkan tidak valid'
            ], 401);
        }

        $customer = Auth::user();
        $tokenResult = $customer->createToken('LaravelPersonalAccessToken');
        $plainTextToken = $tokenResult->accessToken;

        return response([
            'message' => 'Berhasil login',
            'customer' => $customer,
            'token_type' => 'Bearer',
            'access_token' => $plainTextToken
        ]);
    }
}
