<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisKetersediaan;
use Illuminate\Support\Facades\Validator;

class JenisKetersediaanController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $jenis_ketersediaan = JenisKetersediaan::query();
            if ($request->search) {
                $jenis_ketersediaan->where('detail_ketersediaan', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_jenis_ketersediaan', 'detail_ketersediaan'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_jenis_ketersediaan';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $jenis_ketersediaan->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Jenis Ketersediaan tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data jenis ketersediaan',
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
                'detail_ketersediaan' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $jenis_ketersediaan = JenisKetersediaan::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan data jenis ketersediaan',
                'data' => $jenis_ketersediaan
            ], 200);
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
            $jenis_ketersediaan = JenisKetersediaan::find($id);
            if (!$jenis_ketersediaan) throw new \Exception('Jenis Ketersediaan tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'detail_ketersediaan' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $jenis_ketersediaan->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data jenis ketersediaan',
                'data' => $jenis_ketersediaan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Delete
    public function delete($id)
    {
        try {
            $jenis_ketersediaan = JenisKetersediaan::find($id);
            if (!$jenis_ketersediaan) throw new \Exception('Jenis Ketersediaan tidak ditemukan');

            $jenis_ketersediaan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data jenis ketersediaan',
                'data' => $jenis_ketersediaan
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
