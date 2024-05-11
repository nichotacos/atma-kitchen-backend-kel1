<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function showTransaksiInputJarak(Request $request)
    {
        try {
            $transaksis = Transaksi::with([
                'cart.detailCart.produk',
                'cart.detailCart.hampers.produk',
                'alamat',
                'status',
                'jenisPengambilan',
                'cart.detailCart.hampers.kemasan'
            ])
            ->where('id_pengambilan', 1)
            ->where('jarak_pengiriman', 0)
            ->where('id_status', 1);

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
            } elseif ($jarak_pengiriman > 10) {
                $total_setelah_ongkir = $total_harga_produk + 20000;
            } elseif ($jarak_pengiriman > 5) {
                $total_setelah_ongkir = $total_harga_produk + 15000;
            } else {
                $total_setelah_ongkir = $total_harga_produk + 10000;
            }

            $transaksis->id_status = 1;
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
                'cart.detailCart.hampers.kemasan'
            ])
            ->where('id_status', 2);

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

}
