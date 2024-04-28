<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

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
}
