<?php

namespace App\Http\Controllers;
use App\Models\ProdukHampers;
use Illuminate\Http\Request;

class ProdukHampersController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $produkHampers = ProdukHampers::query();

            $data = $produkHampers->get();

            if ($data->isEmpty()) throw new \Exception('Produk hampers tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data Produk hampers',
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
