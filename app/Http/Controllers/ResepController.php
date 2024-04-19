<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Resep;

class ResepController extends Controller
{
    //Show
    public function index()
    {
        try {
            $reseps = Resep::query()->with(['DetailResep', 'Produk']);
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

    //Update
    public function update(Request $request, String $id)
    {
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
    public function delete($id)
    {
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
