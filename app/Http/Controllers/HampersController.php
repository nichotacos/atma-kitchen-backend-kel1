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

    // Store Hampers
    public function storeHampers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_hampers' => 'required|string|max:255',
                'harga_hampers' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $hampers = Hampers::create([
                'nama_hampers' => $request->nama_hampers,
                'harga_hampers' => $request->harga_hampers,
                'id_kemasan' => 5
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menambahkan data hampers',
                "data" => $hampers
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Store Products
    public function storeProducts(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_hampers' => 'required|integer',
                'id_produk' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $hampers = Hampers::find($request->id_hampers);

            if (!$hampers) throw new \Exception('Hampers tidak ditemukan');

            $uniqueEntryCheck = ProdukHampers::where('id_hampers', $request->id_hampers)->where('id_produk', $request->id_produk)->get();

            if (!$uniqueEntryCheck->isEmpty()) throw new \Exception('Produk sudah ada pada hampers');

            $produk_hampers = ProdukHampers::create([
                'id_hampers' => $request->id_hampers,
                'id_produk' => $request->id_produk
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menambahkan data produk pada hampers',
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

    // Update 
    public function update(Request $request, $idHampers)
    {
        try {
            $hampers = ProdukHampers::find($idHampers);

            if (!$hampers) throw new \Exception('Hampers tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'nama_hampers' => 'required|string|max:255',
                'harga_hampers' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $hampers->update([
                'nama_hampers' => $request->nama_hampers,
                'harga_hampers' => $request->harga_hampers
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Berhasil update hampers',
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

    // Delete hampers tertentu
    public function destroy($idHampers)
    {
        try {
            $hampers = Hampers::find($idHampers);

            if (!$hampers) throw new \Exception('Hampers tidak ditemukan');

            $hampers->produk()->detach();

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
            $hampers = Hampers::find($request->id_hampers);
            $produk_hampers = ProdukHampers::where('id_hampers', $request->id_hampers)->where('id_produk', $request->id_produk)->get();

            if (!$produk_hampers) throw new \Exception('Data hampers dan produk tidak ditemukan');

            $hampers->produk()->detach($request->id_produk);

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete hampers dan produk terkait',
                "data" => $produk_hampers
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
