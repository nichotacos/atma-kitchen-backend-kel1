<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\DetailResep;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DetailResepController extends Controller
{
    //Show
    public function index(){
        $detailReseps = DetailResep::all();

        if(count($detailReseps) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detailReseps
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
                'id_role' => 'required|numeric|between:1,21',
                'jumlah' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $detailReseps = DetailResep::create([
                'id_role' => $request->id_role,
                'jumlah' => $request->jumlah
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $detailReseps
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    //ShowById
    public function show($id){
        try {
            $detailReseps = DetailResep::find($id);

            if (!$detailReseps) throw new \Exception("Detail Resep Not Found");

            return response()->json([
                "status" => true,
                "message" => 'Detail Resep Found',
                "data" => $detailReseps
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
            $detailReseps = DetailResep::find($id);

            if (!$detailReseps) throw new \Exception("Detail Resep Not Found");

            $validator = Validator::make($request->all(), [
                'id_role' => 'required|numeric|between:1,21',
                'jumlah' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $detailReseps->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Update Detail Resep Success',
                "data" => $detailReseps
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
            $detailReseps = DetailResep::find($id);

            if (!$detailReseps) throw new \Exception("Detail Resep Not Found");

            $detailReseps->delete();

            return response()->json([
                "status" => true,
                "message" => 'Delete Detail Resep Success',
                "data" => $detailReseps
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
            $detailReseps = DetailResep::whereHas('bahanBaku', function ($query) use ($keyword) {
                $query->where('nama_bahan_baku', 'like', '%' . $keyword . '%');
            })->get();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil mencari Detail Reseps',
                "data" => $detailReseps
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
