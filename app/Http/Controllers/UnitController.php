<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\Validator;


class UnitController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $units = Unit::query();
            if ($request->search) {
                $units->where('nama_unit', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_unit', 'nama_unit'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_unit';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $units->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Unit tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data unit',
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
                'nama_unit' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $unit = Unit::create([
                'nama_unit' => $request->nama_unit,
            ]);

            return response([
                'message' => 'Berhasil menambahkan unit',
                'unit' => $unit
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
            $unit = Unit::find($id);
            if (!$unit) throw new \Exception('Unit tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'nama_unit' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $unit->nama_unit = $request->nama_unit;
            $unit->save();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil mengubah data unit',
                'data' => $unit
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
            $unit = Unit::find($id);

            if (!$unit) throw new \Exception('Unit tidak ditemukan');

            $unit->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete unit',
                "data" => $unit
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
