<?php

namespace App\Http\Controllers;

use App\Models\PengadaanBahanBaku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengadaanBahanBakuController extends Controller
{
    // Show, sort, and search all pengadaan bahan baku
    public function index(Request $request)
    {
        try {
            $pengadaan = PengadaanBahanBaku::with('bahan_baku', 'unit')->get();

            if ($request->has('sort')) {
                $pengadaan = PengadaanBahanBaku::with('bahan_baku', 'unit')->orderBy('harga_total', $request->sort)->get();
            }

            if ($request->has('search')) {
                $pengadaan = PengadaanBahanBaku::with('bahan_baku', 'unit')->where('jumlah_pengadaan', 'like', '%' . $request->search . '%')->get();
            }

            // if ($request->sort_by && in_array($request->sort_by, ['id_hampers', 'nama_hampers, harga_hampers'])) {
            //     $sort_by = $request->sort_by;
            // } else {
            //     $sort_by = 'id_hampers';
            // }

            // if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
            //     $sort_order = $request->sort_order;
            // } else {
            //     $sort_order = 'asc';
            // }

            // $data = $hampers->orderBy($sort_by, $sort_order)->get();
            $data = $pengadaan;

            if ($data->isEmpty()) throw new \Exception('Pengadaan bahan baku tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data pengadaan bahan baku',
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
        $validator = Validator::make($request->all(), [
            'jumlah_pengadaan' => 'required',
            'harga_per_unit' => 'required',
            'harga_total' => 'required',
            'tanggal_pengadaan' => 'required|date',
            'id_bahan_baku' => 'required',
            'id_unit' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $pengadaan = PengadaanBahanBaku::create($request->all());

        return response()->json([
            "status" => true,
            "message" => 'Berhasil menambahkan data pengadaan bahan baku',
            "data" => $pengadaan
        ], 201);
    }

    // Update
    public function update(Request $request, $id)
    {
        $pengadaan = PengadaanBahanBaku::find($id);

        if (!$pengadaan) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengadaan bahan baku tidak ditemukan',
                'data' => []
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jumlah_pengadaan' => 'required',
            'harga_per_unit' => 'required',
            'harga_total' => 'required',
            'tanggal_pengadaan' => 'required',
            'id_bahan_baku' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $pengadaan->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Berhasil mengubah data pengadaan bahan baku',
            'data' => $pengadaan
        ], 201);
    }

    // Delete
    public function destroy($id)
    {
        $pengadaan = PengadaanBahanBaku::find($id);

        if (!$pengadaan) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengadaan bahan baku tidak ditemukan',
                'data' => []
            ], 404);
        }

        $pengadaan->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menghapus data pengadaan bahan baku',
            'data' => $pengadaan
        ], 201);
    }

    //Get one
    public function show($id)
    {
        $pengadaan = PengadaanBahanBaku::find($id);

        if (!$pengadaan) {
            return response()->json([
                'status' => false,
                'message' => 'Data pengadaan bahan baku tidak ditemukan',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Berhasil menampilkan data pengadaan bahan baku',
            'data' => $pengadaan
        ], 200);
    }
}
