<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    
    public function index(Request $request)
    {
        try {
            $bahanBakus = BahanBaku::query()->with('unit');

            if ($request->search) {
                $bahanBakus->where(function ($query) use ($request) {
                    $query->where('nama_bahan_baku', 'like', '%' . $request->search . '%')
                          ->orWhereHas('unit', function (Builder $query) use ($request) {
                              $query->where('nama_unit', 'like', '%' . $request->search . '%');
                          });
                });
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_bahan_baku', 'nama_bahan_baku'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_bahan_baku';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $bahanBakus->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) {
                throw new \Exception('Bahan baku tidak ditemukan');
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data bahan baku',
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
                'nama_bahan_baku' => 'required|string',
                'stok_bahan_baku' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $bahan_bakus = BahanBaku::create([
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
                'nama_bahan_baku' => 'required|string',
                'stok_bahan_baku' => 'required|numeric'
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
// public function index()
// {
//     $bahan_bakus = BahanBaku::all();

//     if (count($bahan_bakus) > 0) {
//         return response([
//             'message' => 'Berhasil menampilkan data',
//             'data' => $bahan_bakus
//         ], 200);
//     }

//     return response([
//         'message' => 'Empty',
//         'data' => null
//     ], 400);
// }