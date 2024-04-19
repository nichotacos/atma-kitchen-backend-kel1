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
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaksi;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\TokenRepository;

class AuthController extends Controller
{
    use HasApiTokens, Notifiable, HasFactory;

    // Register Customer
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:customers|unique:karyawans',
            'email' => 'required|string|email|max:255|unique:customers|unique:karyawans',
            'password' => 'required|string|min:8',
            'nomor_telepon' => ['required', 'regex:/^08\d{9,11}$/', 'unique:customers,nomor_telepon', 'unique:karyawans,nomor_telepon_karyawan'],
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

    public function registerKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_role' => 'required|integer|between:2,4',
            'nama_karyawan' => 'required|string|max:255',
            'nomor_telepon_karyawan' => ['required', 'regex:/^08\d{9,11}$/', 'unique:customers,nomor_telepon|unique:karyawans'],
            'email' => 'required|string|email|max:255|unique:customers|unique:karyawans',
            'username' => 'required|string|max:255|unique:customers|unique:karyawans',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        switch ($request->id_role) {
            case 2:
                $gajiHarian = 80000.00;
                $bonusRajin = 50000.00;
                break;
            case 3:
                $gajiHarian = 120000.00;
                $bonusRajin = 75000.00;
                break;
            case 4:
                $gajiHarian = 160000.00;
                $bonusRajin = 100000.00;
                break;
            default:
                $gajiHarian = 0.00;
                $bonusRajin = 0.00;
        }

        $karyawan = Karyawan::create([
            'id_role' => $request->id_role,
            'nama_karyawan' => $request->nama_karyawan,
            'nomor_telepon_karyawan' => $request->nomor_telepon_karyawan,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'tanggal_rekrut' => now(),
            'gaji_harian' => $gajiHarian,
            'bonus_rajin' => $bonusRajin
        ]);

        return response([
            'message' => 'Berhasil registrasi akun karyawan.',
            'customers' => $karyawan
        ], 201);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();

        $validator = Validator::make($loginData, [
            'username' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $attemptCustomer = Customer::where('username', $loginData['username'])->first();

        if (!$attemptCustomer) {
            $attemptKaryawan = Karyawan::where('username', $loginData['username'])->first();
            if (!$attemptKaryawan) {
                return response([
                    'message' => 'Username tidak ditemukan'
                ], 401);
            }
        }

        if ($attemptCustomer) {
            $credentials = [
                'username' => $attemptCustomer->username,
                'password' => $attemptCustomer->password
            ];

            if (!Auth::guard('customer')->attempt([
                'username' => $credentials['username'],
                'password' => $request->password,
            ])) {
                return response([
                    'message' => 'Data yang diinputkan tidak valid customer'
                ], 401);
            }

            $finalLogin = Auth::guard('customer')->user();
            $tokenResult = $finalLogin->createToken('LaravelPersonalAccessToken');
            $plainTextToken = $tokenResult->accessToken;

            $message = 'Berhasil login sebagai Customer';
        } else if ($attemptKaryawan) {
            $credentials = [
                'username' => $attemptKaryawan->username,
                'password' => $attemptKaryawan->password
            ];

            if (!Auth::guard('employee')->attempt([
                'username' => $credentials['username'],
                'password' => $request->password,
            ])) {
                return response([
                    'message' => 'Data yang diinputkan tidak valid karyawan'
                ], 401);
            }

            $finalLogin = Auth::guard('employee')->user();
            $tokenResult = $finalLogin->createToken('LaravelPersonalAccessToken');
            $plainTextToken = $tokenResult->accessToken;

            switch ($attemptKaryawan['id_role']) {
                case 1:
                    $message = 'Berhasil login sebagai Owner';
                    break;
                case 2:
                    $message = 'Berhasil login sebagai karyawan biasa';
                    break;
                case 3:
                    $message = 'Berhasil login sebagai Admin';
                    break;
                case 4:
                    $message = 'Berhasil login sebagai Manajer Operasional';
                    break;
            }
        }

        return response([
            'message' => $message,
            'data' => $finalLogin,
            'token_type' => 'Bearer',
            'access_token' => $plainTextToken
        ]);
    }

    public function changePasswordKaryawan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $karyawan = Auth::guard('employee')->user();

            if (!$karyawan) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            if (!Hash::check($request->old_password, $karyawan->password)) {
                return response()->json(['error' => 'Password Lama Salah.'], 400);
            }

            $karyawan->password = Hash::make($request->new_password);
            $karyawan->save();

            return response()->json(['message' => 'Password Berhasil Diganti.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
