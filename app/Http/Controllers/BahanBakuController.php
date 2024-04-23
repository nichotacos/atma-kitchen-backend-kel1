<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahan_bakus = BahanBaku::all();

        if (count($bahan_bakus) > 0) {
            return response([
                'message' => 'Berhasil menampilkan data',
                'data' => $bahan_bakus
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
                'id_unit' => 'required|numeric|between:1,4',
                'nama_bahan_baku' => 'required|string',
                'stok_bahan_baku' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $bahan_bakus = BahanBaku::create([
                'id_unit' => $request->id_unit,
                'nama_bahan_baku' => $request->nama_bahan_baku,
                'stok_bahan_baku' => $request->stok_bahan_baku
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $bahan_bakus
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
            $bahan_bakus = BahanBaku::find($id);

            if (!$bahan_bakus) throw new \Exception("Bahan Baku tidak tersedia!");

            return response()->json([
                "status" => true,
                "message" => 'Bahan baku tersedia',
                "data" => $bahan_bakus
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
            $bahan_bakus = BahanBaku::find($id);

            if (!$bahan_bakus) throw new \Exception("Bahan Baku tidak tersedia!");

            $validator = Validator::make($request->all(), [
                'id_unit' => $request->id_unit,
                'nama_bahan_baku' => $request->nama_bahan_baku,
                'stok_bahan_baku' => $request->stok_bahan_baku
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $bahan_bakus->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data bahan baku',
                'data' => $bahan_bakus
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
            $bahan_bakus = BahanBaku::find($id);

            if (!$bahan_bakus) throw new \Exception("Bahan Baku tidak tersedia!");

            $bahan_bakus->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete bahan baku',
                "data" => $bahan_bakus
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
            $bahan_bakus = BahanBaku::where('nama_bahan_baku', 'like', '%' . $keyword . '%')->get();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari bahan baku',
                "data" => $bahan_bakus
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
