<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Customer;

class CustomerController extends Controller
{
    //Show
    public function index(){
        $customers = Customer::all();

        if(count($customers) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $customers
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    //Store
    public function store(Request $request){
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

    //Search
    public function show($id){
        try {
            $customers = Customer::find($id);

            if (!$customers) throw new \Exception("Customer Not Found");

            return response()->json([
                "status" => true,
                "message" => 'Customer Found',
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
    public function update(Request $request, String $id){
        try {
            $customers = Customer::find($id);

            if (!$customers) throw new \Exception("Customer Not Found");

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
    public function delete($id){
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

    //Search
    public function search($keyword)
    {
        try {
            $customers = Customer::where('nama', 'like', '%' . $keyword . '%')->get();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari Customer',
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
   public function showTransaksisByCustomer(){
        try {
            $id_customer = Auth::guard('api')->user()->id;
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
   public function searchTransaksisCustomerByProduct($keyword){
        try {
            $id_customer = Auth::guard('api')->user()->id;
            $transaksis = Transaksi::where('id_customer', $id_customer)->get();

            if ($transaksis->isEmpty()) {
                throw new \Exception($id_customer);
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
