<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $statuses = Status::query();
            if ($request->search) {
                $statuses->where('detail_status', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_status', 'detail_status'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_status';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $statuses->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Status tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data status',
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
                'detail_status' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $status = Status::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menambahkan data status',
                'data' => $status
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
            $status = Status::find($id);
            if (!$status) throw new \Exception('Status tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'detail_status' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $status->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data status',
                'data' => $status
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
            $status = Status::find($id);
            if (!$status) throw new \Exception('Status tidak ditemukan');
            $status->delete();
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menghapus data status',
                'data' => $status
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
