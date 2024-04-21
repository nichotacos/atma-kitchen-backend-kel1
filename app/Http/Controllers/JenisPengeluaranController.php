<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPengeluaran;
use Illuminate\Support\Facades\Validator;

class JenisPengeluaranController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $jenis_pengeluaran = JenisPengeluaran::query();
            if ($request->search) {
                $jenis_pengeluaran->where('detail_jenis_pengeluaran', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_jenis_pengeluaran', 'detail_jenis_pengeluaran'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_jenis_pengeluaran';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $jenis_pengeluaran->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Jenis Pengeluaran tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data jenis pengeluaran',
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
                'detail_jenis_pengeluaran' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $jenis_pengeluaran = JenisPengeluaran::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan data jenis pengeluaran',
                'data' => $jenis_pengeluaran
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
            $jenis_pengeluaran = JenisPengeluaran::find($id);
            if (!$jenis_pengeluaran) throw new \Exception('Jenis Pengeluaran tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'detail_jenis_pengeluaran' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $jenis_pengeluaran->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data jenis pengeluaran',
                'data' => $jenis_pengeluaran
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
    public function destroy($id)
    {
        try {
            $jenis_pengeluaran = JenisPengeluaran::find($id);
            if (!$jenis_pengeluaran) throw new \Exception('Jenis Pengeluaran tidak ditemukan');

            $jenis_pengeluaran->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data jenis pengeluaran',
                'data' => $jenis_pengeluaran
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
