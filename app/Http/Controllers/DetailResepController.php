<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailResep;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DetailResepController extends Controller
{
    //Show
    public function index(Request $request)
    {
        try {
            $detailReseps = DetailResep::query()->with('bahanBaku');
            if ($request->search) {
                $detailReseps->whereHas('bahanBaku', function (Builder $query) use ($request) {
                    $query->where('nama_bahan_baku', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_detail_resep', 'bahan_bakus.nama_bahan_baku'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_detail_resep';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $detailReseps->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Data Detail Resep tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data detail resep',
                'data' => $data
            ], 200);
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
                'id_bahan_baku' => 'required|numeric|between:1,21',
                'jumlah' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $detailReseps = DetailResep::create([
                'id_bahan_baku' => $request->id_bahan_baku,
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

    //Search
    public function show($id)
    {
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
    public function update(Request $request, String $id)
    {
        try {
            $detailReseps = DetailResep::find($id);

            if (!$detailReseps) throw new \Exception("Detail Resep Not Found");

            $validator = Validator::make($request->all(), [
                'id_bahan_baku' => 'required|numeric|between:1,21',
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
    public function delete($id)
    {
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
