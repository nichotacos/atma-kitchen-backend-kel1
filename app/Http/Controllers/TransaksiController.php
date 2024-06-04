<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Hampers;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ]);

            if ($request->search) {
                $transaksis->where(function ($query) use ($request) {
                    $query->where('nomor_nota', 'like', '%' . $request->search . '%')
                        ->orWhereHas('customer', function ($query) use ($request) {
                            $query->where('nama_customer', 'like', '%' . $request->search . '%');
                        });
                });
            }

            if ($request->id_transaksi) {
                $transaksis->where('id_transaksi', $request->id_transaksi);
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_transaksi', 'nomor_nota', 'total_harga_produk', 'total_harga_final', 'tanggal_pemesanan', 'tanggal_pelunasan', 'tanggal_ambil'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_transaksi';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $transaksis->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            return response()->json([
                "status" => true,
                'message' => 'Berhasil menampilkan data transaksi',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    public function storeTransaksi(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_customer' => 'required|numeric',
                'id_pengambilan' => 'required|numeric',
                'id_cart' => 'required|numeric',
                'id_status' => 'required|numeric',
                'id_alamat' => 'nullable|numeric',
                'tanggal_pemesanan' => 'required|date',
                'tanggal_pelunasan' => 'nullable|date',
                'tanggal_ambil' => 'nullable|date',
                'total_harga_produk' => 'required|numeric',
                'jarak_pengiriman' => 'required|numeric',
                'ongkos_kirim' => 'required|numeric',
                'total_setelah_ongkir' => 'required|numeric',
                'poin_sebelumnya' => 'required|numeric',
                'poin_digunakan' => 'required|numeric',
                'total_harga_final' => 'required|numeric',
                'perolehan_poin' => 'required|numeric',
                'nominal_tip' => 'required|numeric',
                'bukti_pembayaran' => 'nullable|string',
            ]);

            $transaction = new Transaksi($validatedData);
            $transaction->nomor_nota = '';
            $transaction->save();

            $transaction->nomor_nota = $transaction->generateNomorNota();
            $transaction->save();

            return response()->json([
                "status" => true,
                "message" => "Transaksi berhasil ditambahkan",
                "data" => $transaction
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    //Coding 2
    public function showTransaksiInputJarak(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->where('id_pengambilan', 1)
                ->where('jarak_pengiriman', 0)
                ->where('id_status', 1);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

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

    public function updateJarakPengiriman(Request $request, $id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) throw new \Exception("Transaksi Not Found");

            $validatedData = $request->validate([
                'jarak_pengiriman' => 'required|numeric|min:0',
            ]);

            $transaksis->update([
                'jarak_pengiriman' => $request->jarak_pengiriman,
            ]);

            $jarak_pengiriman = $request->jarak_pengiriman;
            $total_harga_final = $transaksis->total_harga_final;

            if ($jarak_pengiriman > 15) {
                $total_setelah_ongkir = $total_harga_final + 25000;
                $transaksis->ongkos_kirim = 25000;
            } elseif ($jarak_pengiriman > 10) {
                $total_setelah_ongkir = $total_harga_final + 20000;
                $transaksis->ongkos_kirim = 20000;
            } elseif ($jarak_pengiriman > 5) {
                $total_setelah_ongkir = $total_harga_final + 15000;
                $transaksis->ongkos_kirim = 15000;
            } else {
                $total_setelah_ongkir = $total_harga_final + 10000;
                $transaksis->ongkos_kirim = 10000;
            }

            $transaksis->id_status = 2;
            $transaksis->total_setelah_ongkir = $total_setelah_ongkir;
            $transaksis->total_harga_final = $total_setelah_ongkir;
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Jarak Pengiriman berhasil diperbarui",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal memperbarui jarak pengiriman: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function showTransaksiBelumValid(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->where('id_status', 3);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

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

    public function updateJumlahPembayaran(Request $request, $id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $validatedData = $request->validate([
                'value' => 'required|numeric',
            ]);

            $inputValue = $validatedData['value'];
            $newNominalTip = $inputValue - $transaksis->total_harga_final;
            $transaksis->nominal_tip = $newNominalTip;
            $transaksis->id_status = 5;
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function getSisaKuota(Request $request)
    {
        try {
            $inputtedDate = $request->input('tanggal_ambil');
            $targettedProduct = $request->input('id_produk');

            $transaksis = Transaksi::where('tanggal_ambil', $inputtedDate)
                ->whereHas('cart', function ($query) use ($targettedProduct) {
                    $query->whereHas('detailCart', function ($subQuery) use ($targettedProduct) {
                        $subQuery->where('id_produk', $targettedProduct);
                    });
                })
                ->with('cart.detailCart')
                ->get();

            // echo $transaksis;

            $totalQuantity = $transaksis->sum(function ($transaksi) use ($targettedProduct) {
                return $transaksi->cart->detailCart
                    ->where('id_produk', $targettedProduct)
                    ->sum('jumlah_produk');
            });

            $totalKuota = 10 - $totalQuantity;

            return response()->json([
                "status" => true,
                "message" => "Sisa kuota berhasil didapatkan",
                "data" => $totalKuota
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to get sisa kuota: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function showTransaksiPembayaranValid(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->whereIn('id_status', [5]);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

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
    public function showTransaksiDiproses(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])
                ->whereIn('id_status', [8, 9, 10, 11]);

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

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

    public function getSisaKuotaHampers(Request $request)
    {
        try {
            $inputtedDate = $request->input('tanggal_ambil');
            $hamperId = $request->input('id_hampers');

            $hamper = Hampers::with('produk')->findOrFail($hamperId);
            $products = $hamper->produk;

            $quotas = [];


            foreach ($products as $product) {
                $productId = $product->id_produk;

                $transaksis = Transaksi::where('tanggal_ambil', $inputtedDate)
                    ->whereHas('cart.detailCart', function ($query) use ($productId) {
                        $query->where('id_produk', $productId);
                    })
                    ->with('cart.detailCart')
                    ->get();

                $totalQuantity = $transaksis->sum(function ($transaksi) use ($productId) {
                    return $transaksi->cart->detailCart
                        ->where('id_produk', $productId)
                        ->sum('jumlah_produk');
                });

                $totalKuota = 10 - $totalQuantity;

                $quotas[] = $totalKuota;
            }

            $minQuota = min($quotas);

            return response()->json([
                "status" => true,
                "message" => "Sisa kuota berhasil didapatkan",
                "data" => $minQuota
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to get sisa kuota: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function updateTransaksiSiapDipickup($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            if ($transaksis->id_status != 8) {
                throw new \Exception("Transaksi tidak dalam status diproses");
            }

            if ($transaksis->id_pengambilan == 2) {
                $transaksis->id_status = 9;
            } else {
                $transaksis->id_status = 10;
            }
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

    public function updateTransaksiSudahDipickup($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            if ($transaksis->id_pengambilan == 2) {
                $transaksis->id_status = 11;
            } else {
                $transaksis->id_status = 10;
            }
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Status updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update status" . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function calculatePoint(Request $request)
    {
        try {
            $total_price = $request->input('total_price');
            $birth_date = $request->input('birth_date');

            $point = 0;

            while ($total_price >= 10000) {
                if ($total_price >= 1000000) {
                    $point += 200;
                    $total_price -= 1000000;
                } else if ($total_price >= 500000) {
                    $point += 75;
                    $total_price -= 500000;
                } else if ($total_price >= 100000) {
                    $point += 15;
                    $total_price -= 100000;
                } else if ($total_price >= 10000) {
                    $point += 1;
                    $total_price -= 10000;
                }
            }

            // Ngecek ultah user ga pake tahun
            if ($birth_date) {
                $birthDateWithoutYear = Carbon::parse($birth_date)->format('m-d');
                $todayWithoutYear = now()->format('m-d');

                if ($birthDateWithoutYear === $todayWithoutYear) {
                    $point += $point;
                }
            }

            return response()->json([
                "status" => true,
                "message" => "Point berhasil dihitung",
                "data" => $point
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal menghitung point: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Batal transaksi
    public function showTransaksiBatal(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan',
                'customer'
            ])->whereNull('tanggal_pelunasan')
                ->whereRaw('DATE(tanggal_ambil) <= DATE_ADD(CURDATE(), INTERVAL 1 DAY)');

            $data = $transaksis->orderBy('id_transaksi', 'asc')->get();

            if ($data->isEmpty()) {
                throw new \Exception('Transaksi Tidak Ditemukan');
            }

            foreach ($data as $transaksi) {
                if ($transaksi->id_status != 4) {
                    $transaksi->id_status = 4;

                    foreach ($transaksi->cart->detailCart as $detailCart) {
                        if ($detailCart->produk && $detailCart->id_jenis_ketersediaan == 1) {
                            $produk = Produk::find($detailCart->id_produk);
                            if ($produk) {
                                $produk->stok += $detailCart->jumlah_produk;
                                if ($produk->stok == 0 && $produk->id_jenis_ketersediaan == 2) {
                                    $produk->id_jenis_ketersediaan = 1;
                                }
                                $produk->save();
                            } else {
                                throw new \Exception('Produk not found for id_produk: ' . $detailCart->id_produk);
                            }
                        }

                        if ($detailCart->hampers && $detailCart->id_jenis_ketersediaan == 1) {
                            foreach ($detailCart->hampers->produk as $hamperProduk) {
                                $produk = Produk::find($hamperProduk->id_produk);
                                if ($produk) {
                                    if ($produk->stok == 0 && $produk->id_jenis_ketersediaan == 2) {
                                        $produk->id_jenis_ketersediaan = 1;
                                    }
                                    $produk->stok += $detailCart->jumlah_produk;
                                    $produk->save();
                                } else {
                                    throw new \Exception('Produk not found for id_produk: ' . $hamperProduk->id_produk);
                                }
                            }
                        }
                    }
                    $transaksi->save();
                }
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


    public function updateTransaksiBatal($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $transaksis->id_status = 4;
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function getProductsFromTransaksi(Request $request)
    {
        try {
            $transaksiId = $request->input('id_transaksi');
            $transaksi = Transaksi::find($transaksiId);

            if (!$transaksi) {
                throw new \Exception("Transaksi Not Found");
            }

            $products = $transaksi->cart->detailCart->map(function ($detailCart) {
                if ($detailCart->produk) {
                    return [
                        'id_produk' => $detailCart->id_produk,
                        'nama_produk' => $detailCart->produk->nama_produk,
                        'jumlah_produk' => $detailCart->jumlah_produk,
                        'harga_produk_terkini' => $detailCart->harga_produk_terkini,
                        'harga_total_terkini' => $detailCart->harga_total_terkini,
                    ];
                } else if ($detailCart->hampers) {
                    return [
                        'id_produk' => $detailCart->id_hampers,
                        'nama_produk' => $detailCart->hampers->nama_hampers,
                        'jumlah_produk' => $detailCart->jumlah_produk,
                        'harga_produk_terkini' => $detailCart->harga_produk_terkini,
                        'harga_total_terkini' => $detailCart->harga_total_terkini,
                    ];
                }
            });

            return response()->json([
                "status" => true,
                "message" => "Produk berhasil didapatkan",
                "data" => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal mendapatkan produk: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function terimaPesanan($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $transaksis->id_status = 6;
            $transaksis->save();

            $currentUser = Customer::find($transaksis->id_customer);
            $currentUser->poin += $transaksis->perolehan_poin - $transaksis->poin_digunakan;
            $currentUser->save();

            $transaksis->total_harga_final = $transaksis->total_harga_final - ($transaksis->poin_digunakan * 100);
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function tolakPesanan($id)
    {
        try {
            $transaksis = Transaksi::find($id);

            if (!$transaksis) {
                throw new \Exception("Transaksi Not Found");
            }

            $transaksis->id_status = 7;
            $transaksis->save();

            return response()->json([
                "status" => true,
                "message" => "Nominal tip updated successfully",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Failed to update nominal tip: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function transferSaldo(Request $request)
    {
        try {
            $total_price = $request->input('total_price');
            $birth_date = $request->input('birth_date');

            $point = 0;

            while ($total_price >= 10000) {
                if ($total_price >= 1000000) {
                    $point += 200;
                    $total_price -= 1000000;
                } else if ($total_price >= 500000) {
                    $point += 75;
                    $total_price -= 500000;
                } else if ($total_price >= 100000) {
                    $point += 15;
                    $total_price -= 100000;
                } else if ($total_price >= 10000) {
                    $point += 1;
                    $total_price -= 10000;
                }
            }

            // Ngecek ultah user ga pake tahun
            if ($birth_date) {
                $birthDateWithoutYear = Carbon::parse($birth_date)->format('m-d');
                $todayWithoutYear = now()->format('m-d');

                if ($birthDateWithoutYear === $todayWithoutYear) {
                    $point += $point;
                }
            }

            return response()->json([
                "status" => true,
                "message" => "Point berhasil dihitung",
                "data" => $point
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Gagal menghitung point: " . $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
