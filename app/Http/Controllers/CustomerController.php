<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

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

    //Baru
    //Update
    public function updateProfile(Request $request)
    {
        try {
            $customers = Auth::guard('customer-api')->user();

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
                "message" => 'Update Profile Success',
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

    //Show History Pesanan and search by produk
    public function showTransaksiCustomer(Request $request)
    {
        try {
            $customers = Auth::guard('customer-api')->user();
            $id_customer = $customers->id_customer;

            $transaksis = Transaksi::with(['cart.detailCart.produk', 'cart.detailCart.hampers.produk'])->where('id_customer', $id_customer);

            if ($request->search) {
                $transaksis->whereHas('cart.detailCart.produk', function ($query) use ($request) {
                    $query->where('nama_produk', 'like', '%' . $request->search . '%');
                });
            }

            foreach ($transaksis as $transaksi) {
                $detailCart = $transaksi->cart->detailCart;

                // Check if produk exists in detailCart
                if ($detailCart->produk) {
                    // If produk exists, show produk
                    $data[] = [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_produk' => $detailCart->produk->id_produk,
                        'nama_produk' => $detailCart->produk->nama_produk,
                        // Add other properties of produk as needed
                    ];
                } elseif ($detailCart->hampers) {
                    // If produk doesn't exist and hampers exists, show hampers
                    $data[] = [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_hampers' => $detailCart->hampers->id_hampers,
                        'nama_hampers' => $detailCart->hampers->nama_hampers,
                        // Add other properties of hampers as needed
                    ];
                } else {
                    // Handle case when neither produk nor hampers exist
                    // You can decide how to handle this case based on your requirements
                }
            }

            $data = $transaksis->orderBy('id_transaksi', 'desc')->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                "message" => "Transaksi Ditemukan",
                "data" => $data
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
