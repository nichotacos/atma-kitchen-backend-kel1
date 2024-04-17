<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoPoin;
use Illuminate\Support\Facades\Validator;

class PromoPoinController extends Controller
{
    public function index()
    {
        $promoPoin = PromoPoin::all();

        if (count($promoPoin) > 0) {
            return response([
                'message' => 'Berhasil menampilkan data',
                'data' => $promoPoin
            ], 200);
        }

        return response([
            'message' => 'Data kosong',
            'data' => null
        ], 400);
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

    public function show($id)
    {
        try {
            $promoPoin = PromoPoin::find($id);

            if (!$promoPoin) throw new \Exception("Promo poin tidak ditemukan!");

            return response()->json([
                "status" => true,
                "message" => 'Promo poin ditemukan',
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

    public function search($keyword)
    {
        try {
            $promoPoin = PromoPoin::where('batas_kelipatan', 'like', '%' . $keyword . '%')->get();
            return response()->json([
                'status' => true,
                'message' => 'Search results',
                'data' => $promoPoin
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
