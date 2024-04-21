<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UkuranProduk;
use Illuminate\Support\Facades\Validator;

class UkuranProdukController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $sizes = UkuranProduk::query();
            if ($request->search) {
                $sizes->where('detail_ukuran', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_ukuran', 'detail_ukuran'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_ukuran';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $sizes->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Ukuran tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data ukuran',
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
            $validator = Validator::make($request->all(), [
                'detail_ukuran' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $size = UkuranProduk::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan ukuran',
                'data' => $size
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        try {
            $size = UkuranProduk::find($id);
            if (!$size) throw new \Exception('Ukuran tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'detail_ukuran' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $size->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data ukuran',
                'data' => $size
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Destroy
    public function destroy($id)
    {
        try {
            $size = UkuranProduk::find($id);
            if (!$size) throw new \Exception('Ukuran tidak ditemukan');

            $size->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data ukuran',
                'data' => $size
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
