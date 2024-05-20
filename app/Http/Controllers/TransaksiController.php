<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
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

            if($transaksis->id_pengambilan == 2){
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

            if($transaksis->id_pengambilan == 2){
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

}
