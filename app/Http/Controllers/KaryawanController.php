<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $karyawans = Karyawan::query()->with('role');
            if ($request->search) {
                $karyawans->where('nama_karyawan', 'like', '%' . $request->search . '%');
            }

            if ($request->nama_role) {
                $karyawans->whereHas('roles', function (Builder $query) use ($request) {
                    $query->where('nama_role', 'like', '%' . $request->nama_role . '%');
                });
            }

            if ($request->nomor_telepon_karyawan) {
                $karyawans->where('nomor_telepon_karyawan', 'like', '%' . $request->nomor_telepon_karyawan . '%');
            }

            if ($request->email) {
                $karyawans->where('email', 'like', '%' . $request->email . '%');
            }

            if ($request->username) {
                $karyawans->where('username', 'like', '%' . $request->username . '%');
            }

            if ($request->tanggal_rekrut) {
                $karyawans->where('tanggal_rekrut', '=', $request->tanggal_rekrut);
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_karyawan', 'role.id_role', 'nama_karyawan'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_karyawan';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $karyawans->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Karyawan tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data karyawan',
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

    //Update
    public function update(Request $request, String $id)
    {
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
    public function delete($id)
    {
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
