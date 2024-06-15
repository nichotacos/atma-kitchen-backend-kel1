<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Customer;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use DateTime;
use Illuminate\Support\Carbon;

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
            // $transaksis = Transaksi::with([
            //     'cart.detailCart.produk',
            //     'cart.detailCart.hampers.produk',
            //     'alamat',
            //     'status',
            //     'jenisPengambilan',
            //     'cart.detailCart.hampers.kemasan',
            //     'customer'
            // ])->whereNull('tanggal_pelunasan')
            //     ->whereRaw('DATE(tanggal_ambil) <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)');

            // $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

            // if ($data->isEmpty()) {
            //     throw new \Exception('Transaksi Tidak Ditemukan');
            // }

            // foreach ($data as $transaksi) {
            //     //1 == Ready Stock
            //     //2 == Pre Order
            //     $status_transaksi = 1;

            //     if ($transaksi->cart && $transaksi->cart->detailCart) {
            //         foreach ($transaksi->cart->detailCart as $detailCart) {
            //             if ($detailCart->id_jenis_ketersediaan == 2) {
            //                 $status_transaksi = 2;
            //                 break;
            //             }
            //         }

            //         if ($transaksi->id_status != 4) {
            //             if ($status_transaksi == 1) {
            //                 $tanggal_ambil = new DateTime($transaksi->tanggal_ambil);
            //                 $tanggal_sekarang = new DateTime(Carbon::now()->toDateString());
            //                 if ($tanggal_ambil < $tanggal_sekarang) {
            //                     foreach ($transaksi->cart->detailCart as $detailCart) {
            //                         if ($detailCart->id_produk != null) {
            //                             $produk = $detailCart->produk;
            //                             $produk->stok += $detailCart->jumlah_produk;
            //                             $produk->id_jenis_ketersediaan = 1;
            //                             $produk->save();
            //                         } else {
            //                             foreach ($detailCart->hampers->produk as $hamperProduk) {
            //                                 $produk = Produk::find($hamperProduk->id_produk);
            //                                 if ($produk) {
            //                                     if ($produk->stok == 0 && $produk->id_jenis_ketersediaan == 2) {
            //                                         $produk->id_jenis_ketersediaan = 1;
            //                                     }
            //                                     $produk->stok += $detailCart->jumlah_produk;
            //                                     $produk->save();
            //                                 } else {
            //                                     throw new \Exception('Produk not found for id_produk: ' . $hamperProduk->id_produk);
            //                                 }
            //                             }
            //                         }
            //                     }
            //                     $customer = Customer::find($transaksi->id_customer);
            //                     $customer->poin = $customer->poin + $transaksi->poin_digunakan;
            //                     $customer->save();
            //                     $transaksi->id_status = 4;
            //                     $transaksi->save();
            //                 } else {
            //                     $data = $data->reject(function ($item) use ($transaksi) {
            //                         return $item->id_transaksi === $transaksi->id_transaksi;
            //                     });
            //                 }
            //             } else if ($status_transaksi == 2) {
            //                 foreach ($transaksi->cart->detailCart as $detailCart) {
            //                     if ($detailCart->id_jenis_ketersediaan == 1) {
            //                             if ($detailCart->id_produk != null) {
            //                                 $produk = $detailCart->produk;
            //                                 $produk->stok += $detailCart->jumlah_produk;
            //                                 $produk->id_jenis_ketersediaan = 1;
            //                                 $produk->save();
            //                             } else {
            //                                 foreach ($detailCart->hampers->produk as $hamperProduk) {
            //                                     $produk = Produk::find($hamperProduk->id_produk);
            //                                     if ($produk) {
            //                                         if ($produk->stok == 0 && $produk->id_jenis_ketersediaan == 2) {
            //                                             $produk->id_jenis_ketersediaan = 1;
            //                                         }
            //                                         $produk->stok += $detailCart->jumlah_produk;
            //                                         $produk->save();
            //                                     } else {
            //                                         throw new \Exception('Produk not found for id_produk: ' . $hamperProduk->id_produk);
            //                                     }
            //                                 }
            //                             }
            //                     }
            //                 }
            //                 $customer = Customer::find($transaksi->id_customer);
            //                 $customer->poin = $customer->poin + $transaksi->poin_digunakan;
            //                 $customer->save();
            //                 $transaksi->id_status = 4;
            //                 $transaksi->save();
            //             }
            //         }
            //     } else {
            //         \Log::error('Cart or detailCart is null for transaksi ID: ' . $transaksi->id_transaksi);
            //     }
            // }

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
            $user->notify(new PushNotifikasi);

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

    public function showConfirmTransfer(Request $request)
    {
        try {
            $customers = Customer::with([
                'customer.nama',
                'id',
                'customer.saldo',
                'customer'
            ])
                ->whereIn('id_customer', [1]);

            $data = $customers->orderBy('id_customer', 'asc')->get();

            if ($data->isEmpty()) {
                throw new \Exception('Customer Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                "message" => "Customer Ditemukan",
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
    public function terimaTransfer($id, Request $request) {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->saldo -= $request->input('amount');
            $customer->save();
            return response()->json(['message' => 'Transfer accepted'], 200);
        }
        return response()->json(['message' => 'Customer not found'], 404);
    }
    
    public function tolakTransfer($id, Request $request) {
        // Logic for rejecting transfer
        return response()->json(['message' => 'Transfer rejected'], 200);
    }
}
