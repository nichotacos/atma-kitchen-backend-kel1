<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Customer;
use App\Models\Transaksi;

class CustomerController extends Controller
{
    //Show
    public function index(Request $request)
    {
        try {
            $customers = Customer::query();
            if ($request->search) {
                $customers->where('username', 'like', '%' . $request->search . '%');
            }

            if ($request->nama) {
                $customers->where('nama', 'like', '%' . $request->nama . '%');
            }

            if ($request->nomor_telepon) {
                $customers->where('nomor_telepon', 'like', '%' . $request->nomor_telepon . '%');
            }

            if ($request->email) {
                $customers->where('email', 'like', '%' . $request->email . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_customer', 'tanggal_registrasi'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_customer';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $customers->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Customer tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data customer',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Store
    public function store(Request $request)
    {
        try {
            $customers = Customer::create($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $customers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Update
    public function update(Request $request, String $id)
    {
        try {
            $customers = Customer::find($id);

            if (!$customers) throw new \Exception("Customer Not Found");

            $validator = Validator::make($request->all(), [
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:customers',
                'email' => 'required|string|email|max:255|unique:customers',
                'nomor_telepon' => ['required', 'regex:/^08\d{9,11}$/', 'unique:customers'],
                'tanggal_lahir' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $customers->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Update Customer Success',
                "data" => $customers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Delete
    public function delete($id)
    {
        try {
            $customers = Customer::find($id);

            if (!$customers) throw new \Exception("Customer Not Found");

            $customers->delete();

            return response()->json([
                "status" => true,
                "message" => 'Delete Customer Success',
                "data" => $customers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Show History Pesanan
    public function showTransaksisByCustomer()
    {
        try {
            // $id_customer = Auth::guard('api')->user()->id;
            $id_customer = '6';
            $transaksis = Transaksi::where('id_customer', $id_customer)->get();

            if ($transaksis->isEmpty()) {
                throw new \Exception($id_customer);
            }

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Show History Pesanan
    public function searchTransaksisCustomerByProduct($keyword)
    {
        try {
            $id_customer = Auth::guard('api')->user()->id;
            $id_customer = '1';
            $transaksis = Transaksi::where('id_customer', $id_customer)->get();

            if ($transaksis->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            $products = Produk::where('nama_produk', 'like', '%' . $keyword . '%')->get();

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
