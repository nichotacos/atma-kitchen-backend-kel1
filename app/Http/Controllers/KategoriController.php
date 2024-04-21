<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $categories = Kategori::query();
            if ($request->search) {
                $categories->where('nama_kategori', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_kategori', 'nama_kategori'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_kategori';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $categories->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Kategori tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data kategori',
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
                'nama_kategori' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $category = Kategori::create([
                'nama_kategori' => $request->nama_kategori
            ]);

            return response([
                'message' => 'Berhasil menambahkan kategori',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = Kategori::find($id);
            if (!$category) throw new \Exception('Kategori tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'nama_kategori' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $category->update([
                'nama_kategori' => $request->nama_kategori
            ]);

            return response([
                'message' => 'Berhasil mengubah kategori',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $category = Kategori::find($id);
            if (!$category) throw new \Exception('Kategori tidak ditemukan');

            $category->delete();

            return response([
                'message' => 'Berhasil menghapus kategori',
                'data' => $category
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
