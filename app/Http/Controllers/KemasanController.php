<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kemasan;
use Illuminate\Support\Facades\Validator;

class KemasanController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $packagings = Kemasan::query();
            if ($request->search) {
                $packagings->where('detail_kemasan', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_kemasan', 'detail_kemasan', 'stok_kemasan'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_kemasan';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $packagings->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Kemasan tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data kemasan',
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
                'detail_kemasan' => 'required|string',
                'stok_kemasan' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $packaging = Kemasan::create([
                'detail_kemasan' => $request->detail_kemasan,
                'stok_kemasan' => $request->stok_kemasan
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan data kemasan',
                'data' => $packaging
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
            $packaging = Kemasan::find($id);
            if (!$packaging) throw new \Exception('Kemasan tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'detail_kemasan' => 'required|string',
                'stok_kemasan' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $packaging->update([
                'detail_kemasan' => $request->detail_kemasan,
                'stok_kemasan' => $request->stok_kemasan
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data kemasan',
                'data' => $packaging
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
            $packaging = Kemasan::find($id);
            if (!$packaging) throw new \Exception('Kemasan tidak ditemukan');

            $packaging->delete();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data kemasan',
                'data' => $packaging
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
