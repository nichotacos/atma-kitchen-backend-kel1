<?php

namespace App\Http\Controllers;

use App\Models\DetailCart;
use App\Models\Hampers;
use App\Models\Produk;
use Illuminate\Http\Request;

class DetailCartController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $detailCarts = DetailCart::query()->with('produk', 'hampers');

            $data = $detailCarts->get();

            if ($data->isEmpty()) throw new \Exception('Detail cart tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data detail cart',
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

    // Store
    public function store(Request $request)
    {
        try {
            $detailCart = $request->all();

            $productId = $request->id_produk;
            $hampersId = $request->id_hampers;

            if ($productId != null) {
                $targetProduct = Produk::find($productId);
                if ($targetProduct->stok > 0) {
                    $targetProduct->stok = $targetProduct->stok - $request->jumlah_produk;
                    $targetProduct->save();
                }
            }

            if ($hampersId != null) {
                $targetHampers = Hampers::find($hampersId);

                $hampersProduct = $targetHampers->produk;
                foreach ($hampersProduct as $product) {
                    $targetProduct = Produk::find($product->id_produk);
                    if ($targetProduct->stok > 0) {
                        $targetProduct->stok = $targetProduct->stok - $request->jumlah_produk;
                        $targetProduct->save();
                    }
                }
            }

            $createdDetailCart = DetailCart::create($detailCart);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan detail cart',
                'data' => $createdDetailCart
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => $detailCart
            ], 400);
        }
    }
}
