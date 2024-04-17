<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

use Exception;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

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

    public function show($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) throw new \Exception('Role tidak ditemukan');

            return response()->json([
                'status' => true,
                'message' => 'Role ditemukan',
                'data' => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

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

            $role = Role::udpate($request->all());

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

    public function destroy($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) throw new \Exception('Role tidak ditemukan');

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

    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_role' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $nama_role = $request->nama_role;
            $roles = Role::where('nama_role', 'like', "%$nama_role%")->get();
            return response()->json([
                'status' => true,
                'message' => 'Search results',
                'data' => $roles
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
