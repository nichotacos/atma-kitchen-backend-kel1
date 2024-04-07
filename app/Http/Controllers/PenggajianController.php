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
            $penggajians = Penggajian::create($request->all());

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
}
