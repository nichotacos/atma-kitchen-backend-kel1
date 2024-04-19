<?php

namespace App\Http\Controllers;

use App\Models\Hampers;
use App\Models\ProdukHampers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HampersController extends Controller
{
    // Show, Search, and Sort 
    public function index(Request $request)
    {
        try {
            $hampers = Hampers::query()->with(['kemasan', 'produk']);

            if ($request->sort_by && in_array($request->sort_by, ['id_hampers', 'nama_hampers, harga_hampers'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_hampers';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $hampers->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Hampers tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data hampers',
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
                'id_hampers' => 'required|integer',
                'id_produk' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $produk_hampers = ProdukHampers::create([
                'id_hampers' => $request->id_hampers,
                'id_produk' => $request->id_produk
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $produk_hampers
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Update -- WAIT UPDATE INI MASI BINGUNG
    // public function update(Request $request, $idHampers)
    // {
    //     try {
    //         $hampers = ProdukHampers::find($idHampers);

    //         if(!$hampers) throw new \Exception('Hampers tidak ditemukan');

    //         $validator = Validator::make($request->all(), [
    //             'id_produk' => 'required|integer'
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 400);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             "status" => false,
    //             "message" => $e->getMessage(),
    //             "data" => []
    //         ], 400);
    //     }
    // }

    // Delete hampers tertentu
    public function destroy($idHampers)
    {
        try {
            $hampers = Hampers::find($idHampers);

            if (!$hampers) throw new \Exception('Hampers tidak ditemukan');

            $hampers->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete hampers',
                "data" => $hampers
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Ini nanti buat pivot table
    public function destroyCertain(Request $request)
    {
        try {
            $produk_hampers = ProdukHampers::where('id_hampers', $request->id_hampers)->where('id_produk', $request->id_produk)->get();

            if (!$produk_hampers) throw new \Exception('Data hampers dan produk tidak ditemukan');

            return response()->json($produk_hampers);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
