<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Penggajian;
use Illuminate\Database\Eloquent\Builder;

class PenggajianController extends Controller
{
    //Show
    public function index(Request $request)
    {
        try {
            $penggajians = Penggajian::query()->with('karyawan.role');
            if ($request->search) {
                $penggajians->whereHas('karyawans', function (Builder $query) use ($request) {
                    $query->where('nama_karyawan', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->tanggal_penggajian) {
                $penggajians->where('tanggal_penggajian', '=', $request->tanggal_penggajian);
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_penggajian', 'karyawans.nama_karyawan', 'tanggal_penggajian', 'roles.id_role'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_penggajian';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $penggajians->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Data Penggajian tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data penggajian',
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

    //Store
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id_karyawan' => 'required|numeric',
                'jumlah_hadir' => 'required|numeric',
                'jumlah_bolos' => 'required|numeric',
                'bonus' => 'required|numeric',
                'total_gaji' => 'required|numeric',
                'tanggal_penggajian' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $penggajians = Penggajian::create([
                'id_role' => $request->id_role,
                'jumlah' => $request->jumlah
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $penggajians
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Search
    public function show($id)
    {
        try {
            $penggajians = Penggajian::find($id);

            if (!$penggajians) throw new \Exception("Penggajian Not Found");

            return response()->json([
                "status" => true,
                "message" => 'Penggajian Found',
                "data" => $penggajians
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Update
    public function update(Request $request, String $id)
    {
        try {
            $penggajians = Penggajian::find($id);

            if (!$penggajians) throw new \Exception("Penggajian Not Found");

            $validator = Validator::make($request->all(), [
                'id_karyawan' => 'required|numeric',
                'jumlah_hadir' => 'required|numeric',
                'jumlah_bolos' => 'required|numeric',
                'bonus' => 'required|numeric',
                'total_gaji' => 'required|numeric',
                'tanggal_penggajian' => 'required|date'
            ]);

            $penggajians->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Update Penggajian Success',
                "data" => $penggajians
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //Delete
    public function delete($id)
    {
        try {
            $penggajians = Penggajian::find($id);

            if (!$penggajians) throw new \Exception("Penggajian Not Found");

            $penggajians->delete();

            return response()->json([
                "status" => true,
                "message" => 'Delete Penggajian Success',
                "data" => $penggajians
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
