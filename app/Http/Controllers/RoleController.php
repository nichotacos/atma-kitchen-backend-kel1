<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use Exception;

class RoleController extends Controller
{
    // Show, Search, and Sort
    public function index(Request $request)
    {
        try {
            $roles = Role::query();
            if ($request->search) {
                $roles->where('nama_role', 'like', '%' . $request->search . '%');
            }

            if ($request->sort_by && in_array($request->sort_by, ['id_role', 'nama_role'])) {
                $sort_by = $request->sort_by;
            } else {
                $sort_by = 'id_role';
            }

            if ($request->sort_order && in_array($request->sort_order, ['asc', 'desc'])) {
                $sort_order = $request->sort_order;
            } else {
                $sort_order = 'asc';
            }

            $data = $roles->orderBy($sort_by, $sort_order)->get();

            if ($data->isEmpty()) throw new \Exception('Role tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Berhasil menampilkan data role',
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

    // Store
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_role' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $role = Role::create([
                'nama_role' => $request->nama_role,
            ]);

            return response()->json([
                "status" => true,
                "message" => 'Insert Data Success',
                "data" => $role
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Update
    public function update(Request $request, $id)
    {
        try {
            $role = Role::find($id);

            if (!$role) throw new \Exception('Role tidak ditemukan');

            $validator = Validator::make($request->all(), [
                'nama_role' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $role->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil update data role',
                "data" => $role
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    // Delete
    public function destroy($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) throw new \Exception('Role tidak ditemukan1');

            $role->delete();

            return response()->json([
                "status" => true,
                "message" => 'Berhasil delete role',
                "data" => $role
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
