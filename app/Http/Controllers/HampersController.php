<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    // Show hampers by id
    public function showHampers($idHampers)
    {
        try {
            $hampers = Hampers::with('produk')->find($idHampers);

            if (!$hampers) throw new \Exception('Hampers tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data hampers',
                'data' => $hampers
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
                'gambar_hampers' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $image = $request->file('gambar_hampers');
            $fileName = $image->hashName();
            $image->move(public_path('img/hampers'), $fileName);
            $uploadedImageResponse = basename($fileName);

            $data = $request->all();
            $data['gambar_hampers'] = $uploadedImageResponse;

            $hampers = Hampers::create([
                'nama_hampers' => $request->nama_hampers,
                'harga_hampers' => $request->harga_hampers,
                'gambar_hampers' => $data['gambar_hampers'],
                'id_kemasan' => 6
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
    public function storeProducts($idHampers, $idProduct)
    {
        try {
            $hampers = Hampers::find($idHampers);

            if (!$hampers) throw new \Exception('Hampers tidak ditemukan');

            $uniqueEntryCheck = ProdukHampers::where('id_hampers', $idHampers)->where('id_produk', $idProduct)->get();

            if (!$uniqueEntryCheck->isEmpty()) throw new \Exception('Produk sudah ada pada hampers');

            $produk_hampers = ProdukHampers::create([
                'id_hampers' => $idHampers,
                'id_produk' => $idProduct
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
            $hampers = Hampers::find($idHampers);

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
            $hampers->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menghapus hampers',
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
    public function destroyCertain($idHampers, $idProduct)
    {
        try {
            $hampers = Hampers::find($idHampers);
            $produk_hampers = ProdukHampers::where('id_hampers', $idHampers)->where('id_produk', $idProduct)->get();

            if (!$produk_hampers) throw new \Exception('Data hampers dan produk tidak ditemukan');

            $hampers->produk()->detach($idProduct);

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
