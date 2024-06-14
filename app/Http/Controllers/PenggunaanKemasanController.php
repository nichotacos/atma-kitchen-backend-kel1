<?php

namespace App\Http\Controllers;

use App\Models\PenggunaanKemasan;
use Illuminate\Http\Request;

class PenggunaanKemasanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $penggunaanKemasans = PenggunaanKemasan::query();
            if ($request->search) {
                $penggunaanKemasans->where('jumlah_penggunaan', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_penggunaan_kemasan', 'jumlah_penggunaan', 'tanggal_penggunaan', 'id_kemasan'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_penggunaan_kemasan';
            }

            $data = $penggunaanKemasans->orderBy($sort_by, 'asc')->get();

            if ($data->isEmpty()) throw new \Exception('Penggunaan Kemasan tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data penggunaan kemasan',
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
            $penggunaanKemasan = PenggunaanKemasan::create($request->all());
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
