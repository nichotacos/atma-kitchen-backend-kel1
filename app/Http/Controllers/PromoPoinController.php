<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoPoin;
use Illuminate\Support\Facades\Validator;

class PromoPoinController extends Controller
{
    public function index(Request $request)
    {
        try {
            $promoPoin = PromoPoin::query();
            if ($request->search) {
                $promoPoin = PromoPoin::where('batas_kelipatan', '=', $request->search);
            }

            if ($request->poin_diterima) {
                $promoPoin = PromoPoin::where('poin_diterima', '=', $request->poin_diterima);
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_promo', 'batas_kelipatan'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_promo';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $promoPoin->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Promo poin tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data promo poin',
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
                'batas_kelipatan' => 'required|numeric',
                'poin_diterima' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $promoPoin = PromoPoin::create($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menambah promo poin',
                "data" => $promoPoin
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $promoPoin = PromoPoin::find($id);

            if (!$promoPoin) throw new \Exception("Promo poin tidak ditemukan!");

            $validator = Validator::make($request->all(), [
                'batas_kelipatan' => 'required|numeric',
                'poin_diterima' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $promoPoin->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil update promo poin',
                "data" => $promoPoin
            ], 201);
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
            $promoPoin = PromoPoin::find($id);

            if (!$promoPoin) throw new \Exception("Promo poin tidak ditemukan!");

            $promoPoin->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete promo poin',
                "data" => $promoPoin
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
