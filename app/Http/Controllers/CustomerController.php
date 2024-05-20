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

    //Baru
    //Show Profile
    public function showProfile()
    {
        try {
            $customers = Auth::guard('customer-api')->user();

            if (!$customers) throw new \Exception("Customer Not Found");

            return response()->json([
                "status" => true,
                "message" => 'Show Profile Success',
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

            $transaksis = Transaksi::with(['cart.detailCart.produk', 'cart.detailCart.hampers.produk', 'alamat', 'status', 'jenisPengambilan', 'cart.detailCart.hampers.kemasan'])->where('id_customer', $id_customer);

            if ($request->search) {
                $transaksis->whereHas('cart.detailCart', function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->whereHas('produk', function ($subquery) use ($request) {
                                $subquery->where('nama_produk', 'like', '%' . $request->search . '%');
                            })
                            ->orWhereHas('hampers', function ($subquery) use ($request) {
                                $subquery->whereHas('produk', function ($subsubquery) use ($request) {
                                    $subsubquery->where('nama_produk', 'like', '%' . $request->search . '%');
                                });
                            });
                    });
                });
            }

            foreach ($transaksis as $transaksi) {
                $detailCart = $transaksi->cart->detailCart;

                if ($detailCart->produk) {

                    $data[] = [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_produk' => $detailCart->produk->id_produk,
                        'nama_produk' => $detailCart->produk->nama_produk,

                    ];
                } elseif ($detailCart->hampers) {

                    $data[] = [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_hampers' => $detailCart->hampers->id_hampers,
                        'nama_hampers' => $detailCart->hampers->nama_hampers,
                    ];
                } else {

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

    //Coding 3
    public function showTransaksiSudahDipickup(Request $request)
    {
        try {
            $customers = Auth::guard('customer-api')->user();
            $id_customer = $customers->id_customer;

            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
            ->whereIn('id_status', [10,11])
            ->where('id_customer', $id_customer);

            if ($request->search) {
                $transaksis->whereHas('cart.detailCart', function ($query) use ($request) {
                    $query->where(function ($q) use ($request) {
                        $q->whereHas('produk', function ($subquery) use ($request) {
                                $subquery->where('nama_produk', 'like', '%' . $request->search . '%');
                            })
                            ->orWhereHas('hampers', function ($subquery) use ($request) {
                                $subquery->whereHas('produk', function ($subsubquery) use ($request) {
                                    $subsubquery->where('nama_produk', 'like', '%' . $request->search . '%');
                                });
                            });
                    });
                });
            }

            foreach ($transaksis as $transaksi) {
                $detailCart = $transaksi->cart->detailCart;

                if ($detailCart->produk) {

                    $data[] = [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_produk' => $detailCart->produk->id_produk,
                        'nama_produk' => $detailCart->produk->nama_produk,

                    ];
                } elseif ($detailCart->hampers) {

                    $data[] = [
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_hampers' => $detailCart->hampers->id_hampers,
                        'nama_hampers' => $detailCart->hampers->nama_hampers,
                    ];
                } else {

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

    public function updateTransaksiSelesai($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $transaksis->id_status = 12;
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Status updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update Status: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
