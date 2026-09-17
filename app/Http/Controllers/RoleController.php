<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        return view('settings.role.index', [
            'sidebar' => $sidebar
        ]);
    }
    public function front_table(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
            <button class="btn btn-sm btn-primary" onclick="editRole(' . $row->id . ')"><i class="ti ti-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="deleteRole(' . $row->id . ')"><i class="ti ti-trash"></i></button>
        ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function show(Request $request)
    {
        try {
            $id = $request->id;
            $data = Role::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ]);
            }
            return response()->json([
                'status' => true,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name',
                'description' => 'required|max:255'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            Role::create($validation->validate());
            return response()->json([
                'status' => true,
                'message' => 'Role berhasil dibuat'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function update(Request $request)
    {
        try {
            $id = $request->id;
            $validation = Validator::make($request->all(), [
                'name' => [
                    'required',
                    'max:100',
                    Rule::unique('roles', 'name')->ignore($id),
                ],
                'description' => 'required|max:255'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            $data = Role::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'mesasge' => 'Role tidak ditemukan'
                ]);
            }
            $data->update($validation->validate());
            return response()->json([
                'status' => true,
                'message' => 'Role berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function is_active(Request $request)
    {
        try {
            $id = $request->id;
            $validation = Validator::make($request->all(), [
                'is_active' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            $data = Role::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'mesasge' => 'Role tidak ditemukan'
                ]);
            }
            $data->update($validation);
            return response()->json([
                'status' => true,
                'message' => 'Role berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
