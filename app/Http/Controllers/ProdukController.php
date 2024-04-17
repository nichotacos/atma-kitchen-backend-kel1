<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $products = Produk::all();
        return response([
            'message' => 'Berhasil menampilkan data',
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'jenis_ketersediaan' => 'required|numeric|between:1,2',
                'id_ukuran' => 'required|numeric|between:1,5',
                'id_kategori' => 'required|numeric|between:1,4',
                'id_kemasan' => 'required|numeric|between:1,6',
                'id_penitip' => 'required|numeric',
                'deskripsi' => 'required|string',
                'harga_produk' => 'required|numeric',
                'stok' => 'required|numeric',
                'kuota_harian' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $products = Produk::create([
                'jenis_ketersediaan' => $request->jenis_ketersediaan,
                'id_ukuran' => $request->id_ukuran,
                'id_kategori' => $request->id_kategori,
                'id_kemasan' => $request->id_kemasan,
                'id_penitip' => $request->id_penitip,
                'deskripsi' => $request->deskripsi,
                'harga_produk' => $request->harga_produk,
                'stok' => $request->stok,
                'kuota_harian' => $request->kuota_harian
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $products
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $products = Produk::find($id);

            if (!$products) throw new \Exception("Produk tidak ditemukan!");

            return response()->json([
                "status" => true,
                "message" => 'Produk ditemukan',
                "data" => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $products = Produk::find($id);

            if (!$products) throw new \Exception("Produk tidak ditemukan!");

            $validator = Validator::make($request->all(), [
                'jenis_ketersediaan' => 'required|numeric|between:1,2',
                'id_ukuran' => 'required|numeric|between:1,5',
                'id_kategori' => 'required|numeric|between:1,4',
                'id_kemasan' => 'required|numeric|between:1,6',
                'id_penitip' => 'required|numeric',
                'deskripsi' => 'required|string',
                'harga_produk' => 'required|numeric',
                'stok' => 'required|numeric',
                'kuota_harian' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $products->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data produk',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $products = Produk::find($id);

            if (!$products) throw new \Exception("Produk tidak ditemukan!");

            $products->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete produk',
                "data" => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');
            $products = Produk::where('nama_produk', 'like', '%' . $keyword . '%')->get();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari produk',
                "data" => $products
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
