<?php

namespace App\Http\Controllers;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    //Show
    public function index(){
        $karyawans = Karyawan::all();

        if(count($karyawans) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $karyawans
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
            $karyawans = Karyawan::create($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $karyawans
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
            $karyawans = Karyawan::find($id);

            if (!$karyawans) throw new \Exception("Karyawan Not Found");

            return response()->json([
                "status" => true,
                "message" => 'Karyawan Found',
                "data" => $karyawans
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
            $karyawans = Karyawan::find($id);

            if (!$karyawans) throw new \Exception("Karyawan Not Found");

            $karyawans->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Update Karyawan Success',
                "data" => $karyawans
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
            $karyawans = Karyawan::find($id);

            if (!$karyawans) throw new \Exception("Karyawan Not Found");

            $karyawans->delete();

            return response()->json([
                "status" => true,
                "message" => 'Delete Karyawan Success',
                "data" => $karyawans
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
