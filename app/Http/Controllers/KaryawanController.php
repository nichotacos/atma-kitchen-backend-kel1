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

            $validator = Validator::make($request->all(), [
                'id_role' => 'required|numeric|between:2,4',
                'nama_karyawan' => 'required|string|max:255',
                'nomor_telepon_karyawan' => ['required', 'regex:/^08\d{9,11}$/', 'unique:karyawans'],
                'email' => 'required|string|email|max:255|unique:karyawans',
                'username' => 'required|string|max:255|unique:karyawans',
                'password' => 'required|string|min:8',
            ]);

            $karyawans->update($request->all());

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $customers->update($request->all());

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

    public function search($keyword)
    {
        try {
            $karyawans = Karyawan::where('nama_karyawan', 'like', '%' . $keyword . '%')->get();
            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari karyawan',
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
