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
   public function showTransaksisCustomerByProduct(){
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

}
