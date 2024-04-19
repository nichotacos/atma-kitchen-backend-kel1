<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResepController extends Controller
{
    //Show
    public function index(){
        $reseps = Resep::all();

        if(count($reseps) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $reseps
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
                'id_detail_resep' => 'required|numeric',
                'id_produk' => 'required|numeric|between:1,19'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $reseps = Resep::create([
                'id_detail_resep' => $request->id_detail_resep,
                'id_produk' => $request->id_produk
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $reseps
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
            $reseps = Resep::find($id);

            if (!$reseps) throw new \Exception("Resep Not Found");

            return response()->json([
                "status" => true,
                "message" => 'Resep Found',
                "data" => $reseps
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
            $reseps = Resep::find($id);

            if (!$reseps) throw new \Exception("Resep Not Found");

            $validator = Validator::make($request->all(), [
                'id_detail_resep' => 'required|numeric',
                'id_produk' => 'required|numeric|between:1,19'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $reseps->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Update Resep Success',
                "data" => $reseps
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
            $reseps = Resep::find($id);

            if (!$reseps) throw new \Exception("Resep Not Found");

            $reseps->delete();

            return response()->json([
                "status" => true,
                "message" => 'Delete Resep Success',
                "data" => $reseps
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
