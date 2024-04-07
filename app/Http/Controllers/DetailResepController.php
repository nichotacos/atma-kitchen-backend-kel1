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
            $detailReseps = DetailResep::create($request->all());

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

    //Search
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
}
