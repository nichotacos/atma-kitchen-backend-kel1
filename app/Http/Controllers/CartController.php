<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //Index
    public function index(Request $request)
    {
        try {
            $carts = Cart::query();
            if ($request->search) {
                $carts->where('id_cart', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_cart', 'id_customer', 'id_produk', 'jumlah_produk', 'total_harga', 'status'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_cart';
            }

            $data = $carts->orderBy($sort_by, 'asc')->get();

            if ($data->isEmpty()) throw new \Exception('Cart tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data cart',
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
            $cart = Cart::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Get latest cart
    public function getLatestCart()
    {
        try {
            $cart = Cart::latest()->first();
            if ($cart == null) {
                throw new \Exception('Cart tidak ditemukan');
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data cart',
                'data' => $cart
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
