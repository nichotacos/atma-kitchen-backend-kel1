<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Penggajian;

class PenggajianController extends Controller
{
    //Show
    public function index(){
        $penggajians = Penggajian::all();

        if(count($penggajians) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $penggajians
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
    public function show($id){
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
    public function update(Request $request, String $id){
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
    public function delete($id){
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

    //Search
    public function search($keyword)
    {
        try {
            $penggajians = Penggajian::whereHas('karyawan', function ($query) use ($keyword) {
                $query->where('nama_karyawan', 'like', '%' . $keyword . '%');
            })->get();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari Penggajian',
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
