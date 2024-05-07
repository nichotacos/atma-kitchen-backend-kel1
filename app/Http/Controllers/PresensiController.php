<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Presensi;

class PresensiController extends Controller
{
    //Show
    public function index(Request $request)
    {
        try {
            $presensis = Presensi::query()->with('karyawan.role');
            if ($request->search) {
                $presensis->where('tanggal_bolos', '=', $request->search());
            }

            if ($request->nama_karyawan) {
                $presensis->whereHas('karyawan', function (Builder $query) use ($request) {
                    $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
                });
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_presensi', 'tanggal_bolos', 'karyawan.nama_karyawan'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_presensi';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'desc';
            }

            $data = $presensis->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Data Presensi tidak ditemukan');

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $presensis
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
    public function show($id){
        try {
            $validator = Validator::make($request->all(), [
                'id_karyawan' => 'required|numeric',
                'tanggal_bolos' => 'required|date|before_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $presensis = Presensi::create([
                'id_karyawan' => $request->id_karyawan,
                'tanggal_bolos' => $request->tanggal_bolos
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Presensi Found',
                "data" => $presensis
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
    public function update(Request $request, String $id){
        try {
            $presensis = Presensi::find($id);

            if (!$presensis) throw new \Exception("Presensi Not Found");

            $validator = Validator::make($request->all(), [
                'id_karyawan' => 'required|numeric',
                'tanggal_bolos' => 'required|date|before_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $presensis->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Update Presensi Success',
                "data" => $presensis
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
    public function destroy($id)
    {
        try {
            $presensis = Presensi::find($id);

            if (!$presensis) throw new \Exception("Presensi Not Found");

            $presensis->delete();

            return response()->json([
                "status" => true,
                "message" => 'Delete Presensi Success',
                "data" => $presensis
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
