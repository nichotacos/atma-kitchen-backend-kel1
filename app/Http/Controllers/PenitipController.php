<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PenitipController extends Controller
{
    public function index()
    {
        $penitips = Penitip::all();

        if (count($penitips) > 0) {
            return response([
                'message' => 'Berhasil menampilkan data',
                'data' => $penitips
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
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

            if (!$penitips) throw new \Exception("Nama penitip tidak ditemukan!");

            return response()->json([
                "status" => true,
                "message" => 'Nama penitip ditemukan',
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

            if (!$penitips) throw new \Exception("Nama penitip tidak ditemukan!");

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

            if (!$penitips) throw new \Exception("Nama penitip tidak ditemukan!");

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
            $penitips = Penitip::where('nama_penitip', 'like', '%' . $keyword . '%')->get();
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
