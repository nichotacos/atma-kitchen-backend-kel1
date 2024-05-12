<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use Illuminate\Http\Request;

class PenitipController extends Controller
{
    public function index(Request $request)
    {
        try {
            $penitip = Penitip::query();
            if ($request->search) {
                $penitip->where('nama_penitip', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_penitip', 'nama_penitip'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_penitip';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $penitip->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Data Penitip tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data penitip',
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
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_penitip' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $penitips = Penitip::create([
                'nama_penitip' => $request->nama_penitip,
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $penitips
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
            $penitips = Penitip::find($id);

            if (!$penitips) throw new \Exception("Data Penitip tidak ditemukan!");

            return response()->json([
                "status" => true,
                "message" => 'Data Penitip ditemukan',
                "data" => $penitips
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
            $penitips = Penitip::find($id);

            if (!$penitips) throw new \Exception("Data Penitip tidak ditemukan!");

            $validator = Validator::make($request->all(), [
                'nama_penitip' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $penitips->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data penitip',
                'data' => $penitips
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
            $penitips = Penitip::find($id);

            if (!$penitips) throw new \Exception("Data Penitip tidak ditemukan!");

            $penitips->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete data penitip',
                "data" => $penitips
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function search($keyword)
    {
        try {
            $penitips = Penitip::where('penitip', 'like', '%' . $keyword . '%')->get();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari data penitip',
                "data" => $penitips
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
