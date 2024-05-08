<?php

namespace App\Http\Controllers;
use App\Models\DetailCart;
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
}
