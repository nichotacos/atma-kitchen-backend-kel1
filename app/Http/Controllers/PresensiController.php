<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Presensi;

class PresensiController extends Controller
{
    //Show
    public function index(){
        $presensis = Presensi::all();

        if(count($presensis) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $presensis
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    //Store
    public function store(Request $request){
        try {
            $presensis = Presensi::create($request->all(), [
                'id_karyawan' => 'required|numeric',
                'tanggal_presensi' => 'required|date|before_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $presensis = Presensi::create([
                'id_role' => $request->id_role,
                'jumlah' => $request->jumlah
            ]);

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
            $presensis = Presensi::find($id);

            if (!$presensis) throw new \Exception("Presensi Not Found");

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

            $presensis = Presensi::create($request->all(), [
                'id_karyawan' => 'required|numeric',
                'tanggal_presensi' => 'required|date|before_or_equal:today',
            ]);

            $presensis->update($request->all());

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

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
    public function delete($id){
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

    //Search
    public function search($keyword)
    {
        try {
            $presensis = Presensi::whereHas('karyawan', function ($query) use ($keyword) {
                $query->where('nama_karyawan', 'like', '%' . $keyword . '%');
            })->get();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari Presensi Karyawan',
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
