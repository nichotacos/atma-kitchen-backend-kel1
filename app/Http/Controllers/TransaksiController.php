<?php

namespace App\Http\Controllers;

use App\Models\Hampers;
use App\Models\Transaksi;
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
                // 'nomor_nota' => 'required|string',
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
                'poin_digunakan' => 'required|numeric',
                'total_harga_final' => 'required|numeric',
                'perolehan_poin' => 'required|numeric',
                'nominal_tip' => 'required|numeric',
                'bukti_pembayaran' => 'nullable|string',
            ]);

            $tempTransaction = new Transaksi($validatedData);
            $tempTransaction->save();

            $tempTransaction->nomor_nota = $tempTransaction->generateNomorNota();
            $tempTransaction->save();

            $transaksis = Transaksi::create($tempTransaction);

            return response()->json([
                "status" => true,
                "message" => "Transaksi berhasil ditambahkan",
                "data" => $transaksis
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

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
            $total_harga_produk = $transaksis->total_harga_produk;

            if ($jarak_pengiriman > 15) {
                $total_setelah_ongkir = $total_harga_produk + 25000;
                $transaksis->ongkos_kirim = 25000;
            } elseif ($jarak_pengiriman > 10) {
                $total_setelah_ongkir = $total_harga_produk + 20000;
                $transaksis->ongkos_kirim = 20000;
            } elseif ($jarak_pengiriman > 5) {
                $total_setelah_ongkir = $total_harga_produk + 15000;
                $transaksis->ongkos_kirim = 15000;
            } else {
                $total_setelah_ongkir = $total_harga_produk + 10000;
                $transaksis->ongkos_kirim = 10000;
            }

            $transaksis->id_status = 2;
            $transaksis->total_setelah_ongkir = $total_setelah_ongkir;
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

    public function getSisaKuota(Request $request)
    {
        try {
            $inputtedDate = $request->input('tanggal_ambil');
            $targettedProduct = $request->input('id_produk');

            $transaksis = Transaksi::where('tanggal_ambil', $inputtedDate)
                ->whereHas('cart.detailCart', function ($query) use ($targettedProduct) {
                    $query->where('id_produk', $targettedProduct);
                })
                ->with('cart.detailCart')
                ->get();

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
}
