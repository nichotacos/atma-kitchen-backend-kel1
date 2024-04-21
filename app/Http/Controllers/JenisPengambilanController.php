<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPengambilan;
use Illuminate\Support\Facades\Validator;

class JenisPengambilanController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $jenis_pengambilan = JenisPengambilan::query();
            if ($request->search) {
                $jenis_pengambilan->where('detail_pengambilan', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_pengambilan', 'detail_pengambilan'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_pengambilan';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $jenis_pengambilan->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Jenis Pengambilan tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data jenis pengambilan',
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
                'detail_pengambilan' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $jenis_pengambilan = JenisPengambilan::create([
                'detail_pengambilan' => $request->detail_pengambilan
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan jenis pengambilan',
                'data' => $jenis_pengambilan
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
            $jenis_pengambilan = JenisPengambilan::find($id);
            if (!$jenis_pengambilan) throw new \Exception('Jenis Pengambilan tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'detail_pengambilan' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $jenis_pengambilan->update([
                'detail_pengambilan' => $request->detail_pengambilan
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data jenis pengambilan',
                'data' => $jenis_pengambilan
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
            $jenis_pengambilan = JenisPengambilan::find($id);
            if (!$jenis_pengambilan) throw new \Exception('Jenis Pengambilan tidak ditemukan');

            $jenis_pengambilan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data jenis pengambilan',
                'data' => $jenis_pengambilan
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
            $jenis_pengambilan = JenisPengambilan::find($id);
            if (!$jenis_pengambilan) throw new \Exception('Jenis Pengambilan tidak ditemukan');

            $jenis_pengambilan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data jenis pengambilan',
                'data' => $jenis_pengambilan
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
