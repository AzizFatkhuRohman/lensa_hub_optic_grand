<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        return view('settings.users.index', [
            'sidebar' => $sidebar
        ]);
    }
    public function front_table(Request $request)
    {
        if ($request->ajax()) {
            $data = User::front_table();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('company', function ($row) {
                    return $row->company->company_name;
                })
                ->addColumn('role', function ($row) {
                    return $row->role->name;
                })
                ->addColumn('status', function ($row) {
                    if ((int) $row->is_active === 1) {
                        return '<span class="badge bg-primary">Active</span>';
                    }

                    return '<span class="badge bg-warning">Not active</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
            <button class="btn btn-sm btn-primary" onclick="editUser(' . $row->id . ')"><i class="ti ti-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="deleteUser(' . $row->id . ')"><i class="ti ti-trash"></i></button>
        ';
                })
                ->rawColumns(['role', 'status', 'action'])
                ->make(true);
        }
    }
    public function show(Request $request)
    {
        try {
            $id = $request->user_id;
            $data = User::with(['role:id,name', 'company:id,company_name'])->find($id);
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
                'company_id' => 'required',
                'role_id' => 'required',
                'name' => 'required|max:100',
                'username' => 'required|max:50|unique:users,username',
                'email' => 'required|max:100|unique:users,email',
                'password' => 'nullable|max:20'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            $data = [
                'company_id' => $request->company_id,
                'role_id' => $request->role_id,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'is_active' => $request->is_active
            ];
            $data['password'] = $request->password;
            if (!$request->password) {
                $data['password'] = $request->username;
            }
            $user = User::create($data);
            return response()->json([
                'status' => true,
                'id' => $user->id,
                'message' => 'User berhasil dibuat'
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
                'company_id' => 'required',
                'role_id' => 'required',
                'name' => 'required|max:100',
                'username' => [
                    'required',
                    'max:100',
                    Rule::unique('users', 'username')->ignore($id),
                ],
                'email' => [
                    'required',
                    'max:100',
                    Rule::unique('users', 'email')->ignore($id),
                ],
                'password' => 'nullable|min:8|max:20',
                'is_active' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            $data = User::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'mesasge' => 'User tidak ditemukan'
                ]);
            }
            $update = [
                'company_id' => $request->company_id,
                'role_id' => $request->role_id,
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'is_active' => $request->is_active
            ];
            if ($request->password) {
                $update['password'] = $request->password;
            }
            $data->update($update);
            return response()->json([
                'status' => true,
                'message' => 'User berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function company_id(Request $request)
    {
        $search = $request->input('search');
        $data = User::company_id($search);
        return response()->json($data);
    }
    public function role_id(Request $request)
    {
        $search = $request->input('search');
        $data = User::role_id($search);
        return response()->json($data);
    }
    public function menu_list(Request $request)
    {
        $search = $request->input('search');
        $data = User::menu_list($search);
        return response()->json($data);
    }
    public function store_menu_access(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $menu_id = $request->menu_id;
            $userData = User::find($user_id);
            if (!$userData) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ]);
            }
            $menuAccess = MenuAccess::where('user_id', $user_id)->where('menu_id', $menu_id)->first();
            if ($menuAccess) {
                return response()->json([
                    'status' => false,
                    'message' => 'Menu sudah digunakan'
                ]);
            }
            MenuAccess::create(['user_id' => $user_id, 'menu_id' => $menu_id]);
            return response()->json([
                'status' => true,
                'message' => 'Menu akses berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function menu_access(Request $request)
    {
        $user_id = $request->user_id;
        if ($request->ajax()) {
            $data = MenuAccess::front_table($user_id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('level', function ($row) {
                    return $row->menu->level;
                })
                ->addColumn('name', function ($row) {
                    return $row->menu->name;
                })
                ->addColumn('parent_id', function ($row) {
                    if (!$row->menu || !$row->menu->parent_id) {
                        return '-';
                    }

                    return Menu::where('id', $row->menu->parent_id)
                        ->value('name') ?? '-';
                })
                ->addColumn('icon', function ($row) {
                    return '<i class="' . $row->menu->icon . '"></i>';
                })
                ->addColumn('url', function ($row) {
                    return $row->menu->url;
                })
                ->addColumn('action', function ($row) {
                    return '
            <button class="btn btn-sm btn-danger" onclick="deleteMenuAccess(' . $row->id . ')"><i class="ti ti-trash"></i></button>
        ';
                })
                ->rawColumns(['name', 'parent_id', 'icon', 'action'])
                ->make(true);
        }
    }
    public function delete_menu_access(Request $request)
    {
        try {
            $id = $request->id;
            $menuAccess = MenuAccess::find($id);
            if (!$menuAccess) {
                return response()->json([
                    'status' => false,
                    'message' => 'Menu akses tidak ditemukan'
                ]);
            }
            MenuAccess::find($id)->delete();
            return response()->json([
                'status' => true,
                'message' => 'Menu akses berhasil di hapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function delete(Request $request)
    {
        try {
            $user_id = $request->id;
            $user = User::find($user_id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User tidak ditemukan'
                ]);
            }
            User::find($user_id)->update(['is_active' => 0]);
            return response()->json([
                'status' => true,
                'message' => 'User berhasil dinonaktifkan'
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
            $data = User::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'mesasge' => 'User tidak ditemukan'
                ]);
            }
            $data->update($validation);
            return response()->json([
                'status' => true,
                'message' => 'User berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
