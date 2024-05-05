<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use Illuminate\Http\Request;

class PenitipController extends Controller
{
    public function index(Request $request)
    {
        try {
            $penitip = Penitip::query();
            if ($request->search) {
                $penitip->where('nama_penitip', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_penitip', 'nama_penitip'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_penitip';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $penitip->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Penitip tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data penitip',
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
}
