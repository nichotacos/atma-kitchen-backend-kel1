<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;

class AlamatController extends Controller
{
    public function index(Request $request)
    {
        try {
            $alamats = Alamat::query();
            if ($request->search) {
                $alamats->where('detail_alamat', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_alamat', 'detail_alamat'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_alamat';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $alamats->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Alamat tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data alamat',
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

    public function showAlamatByCustomer($id)
    {
        try {
            $alamats = Alamat::query()->where('id_customer', $id)->get();

            if ($alamats->isEmpty()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Customer tidak memiliki alamat',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data alamat',
                'data' => $alamats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function getAlamat($id)
    {
        try {
            $alamat = Alamat::find($id);
            if ($alamat == null) {
                throw new \Exception('Alamat tidak ditemukan');
            }
            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data alamat',
                'data' => $alamat
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
