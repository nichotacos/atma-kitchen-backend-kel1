<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    public function index()
    {
        $pengeluarans = Pengeluaran::all();

        if (count($pengeluarans) > 0) {
            return response([
                'message' => 'Berhasil menampilkan data pengeluaran',
                'data' => $pengeluarans
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
                'deskripsi' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $pengeluarans = Pengeluaran::create([
                'deskripsi' => $request->deskripsi,
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $pengeluarans
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
            $pengeluarans = Pengeluaran::find($id);

            if (!$pengeluarans) throw new \Exception("Pengeluaran tidak ditemukan!");

            return response()->json([
                "status" => true,
                "message" => 'Pengeluaran ditemukan',
                "data" => $pengeluarans
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
            $pengeluarans = Pengeluaran::find($id);

            if (!$pengeluarans) throw new \Exception("Pengeluaran tidak ditemukan!");

            $validator = Validator::make($request->all(), [
                'deskripsi' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $pengeluarans->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data pengeluaran',
                'data' => $pengeluarans
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
            $pengeluarans = Pengeluaran::find($id);

            if (!$pengeluarans) throw new \Exception("Pengeluaran tidak ditemukan!");

            $pengeluarans->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete pengeluaran',
                "data" => $pengeluarans
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
            $pengeluarans = Pengeluaran::where('pengeluaran', 'like', '%' . $keyword . '%')->get();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari data pengeluaran',
                "data" => $pengeluarans
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
