<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        return view('settings.menus.index', [
            'sidebar' => $sidebar
        ]);
    }
    public function front_table(Request $request)
    {
        if ($request->ajax()) {
            $data = Menu::orderBy('parent_id', 'asc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('parent_name', function ($row) {
                    return Menu::where('id', $row->parent_id)
                        ->value('name') ?? '-';
                })
                ->addColumn('icon', function ($row) {
                    return '<i class="' . $row->icon . '"></i>';
                })
                ->addColumn('action', function ($row) {
                    return '
            <button class="btn btn-sm btn-primary" onclick="editMenu(' . $row->id . ')"><i class="ti ti-edit"></i></button>
            <button class="btn btn-sm btn-danger" onclick="deleteMenu(' . $row->id . ')"><i class="ti ti-trash"></i></button>
        ';
                })
                ->rawColumns(['parent_name', 'icon', 'action'])
                ->make(true);
        }
    }
    public function show(Request $request)
    {
        try {
            $id = $request->id;
            $data = Menu::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Menu tidak ditemukan'
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
                'level' => 'required',
                'parent_id' => 'nullable',
                'name' => 'required:max:100|unique:menus,name',
                'icon' => 'nullable',
                'url' => 'nullable|max:100',
                'description' => 'nullable|max:255'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            Menu::create($validation->validated());
            return response()->json([
                'status' => true,
                'message' => 'Menu berhasil dibuat'
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
                'level' => 'required',
                'parent_id' => 'nullable',
                'name' => [
                    'required',
                    'max:100',
                    Rule::unique('menus', 'name')->ignore($id),
                ],
                'icon' => 'nullable',
                'url' => 'nullable|max:100',
                'description' => 'nullable|max:255'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            $data = Menu::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'mesasge' => 'Menu tidak ditemukan'
                ]);
            }
            $data->update($validation->validated());
            return response()->json([
                'status' => true,
                'message' => 'Menu berhasil diubah'
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
            $id = $request->id;
            $data = Menu::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Menu tidak ditemukan'
                ]);
            }
            $data->delete();
            return response()->json([
                'status' => true,
                'message' => 'Menu berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function parent_id(Request $request)
    {
        $search = $request->input('search');
        $level  = $request->input('level');
        $data = Menu::parent_show($level, $search);
        return response()->json($data);
    }
}
