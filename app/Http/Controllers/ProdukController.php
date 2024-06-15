<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            // sementara hapus detail resep
            $products = Produk::query()->with(['JenisKetersediaan', 'UkuranProduk', 'Kategori', 'Kemasan', 'Penitip']);
            if ($request->search) {
                $products->where('nama_produk', 'like', '%' . $request->search . '%');
            }

            if ($request->id_produk) {
                $products->where('id_produk', $request->id_produk);
            }

            if ($request->kategori) {
                $products->whereHas('Kategori', function (Builder $query) use ($request) {
                    $query->where('nama_kategori', 'like', '%' . $request->kategori . '%');
                });
            }

            if ($request->jenis_ketersediaan) {
                $products->whereHas('JenisKetersediaan', function (Builder $query) use ($request) {
                    $query->where('detail_ketersediaan', 'like', '%' . $request->jenis_ketersediaan . '%');
                });
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_produk', 'nama_produk'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_produk';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $products->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Produk tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data produk',
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
            $data = $request->all();
            $validator = Validator::make($data, [
                'id_jenis_ketersediaan' => 'required|numeric|between:1,2',
                'id_ukuran' => 'required|numeric',
                'id_kategori' => 'required|numeric',
                'id_kemasan' => 'required|numeric|',
                'id_penitip' => 'required|numeric',
                'deskripsi_produk' => 'required|string',
                'harga_produk' => 'required|numeric',
                'stok' => 'required|numeric',
                'kuota_harian' => 'required|numeric',
                'gambar_produk' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $image = $request->file('gambar_produk');
            $fileName = $image->hashName();
            $image->move(public_path('img/produk'), $fileName);
            $uploadedImageResponse = basename($fileName);

            $data['gambar_produk'] = $uploadedImageResponse;
            $data['is_deleted'] = 0;

            $products = Produk::create($data);

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menambahkan produk',
                "data" => $products
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
    public function update(Request $request, int $id)
    {
        try {
            $products = Produk::find($id);

            if (!$products) throw new \Exception("Produk tidak ditemukan!");

            $validator = Validator::make($request->all(), [
                'id_jenis_ketersediaan' => 'required|numeric|between:1,2',
                'id_ukuran' => 'required|numeric',
                'id_kategori' => 'required|numeric',
                'id_kemasan' => 'required|numeric|',
                'id_penitip' => 'required|numeric',
                'deskripsi_produk' => 'required|string',
                'harga_produk' => 'required|numeric',
                'stok' => 'required|numeric',
                'kuota_harian' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $products->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Berhasil update data produk',
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Delete
    public function destroy($id)
    {
        try {
            $products = Produk::find($id);

            if (!$products) throw new \Exception("Produk tidak ditemukan!");

            Storage::disk('public')->delete('img/produk' . $products->gambar_produk);

            $products->is_deleted = 1;
            $products->save();

            return response()->json([
                "status" => true,
                "message" => 'Produk berhasil dihapus',
                "data" => $products
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
