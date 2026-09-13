<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        return view('menus.index');
    }
    public function front_table(Request $request)
    {
        if ($request->ajax()) {

            $data = Menu::front_table();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <button type="button" class="btn btn-sm btn-primary">
                        Edit
                    </button>
                    <button type="button" class="btn btn-sm btn-danger">
                        Delete
                    </button>
                ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
    public function show_menu(Request $request)
    {
        try {
            $id = $request->id;
            $check = Menu::find($id);
            if (!$check) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            return response()->json([
                'status' => true,
                'data' => $check
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => true,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function add_menu(Request $request)
    {
        try {
            // $level = $request->level;
            // $parent_id = $request->parent_id;
            // $name = $request->name;
            // $icon = $request->icon;
            // $url = $request->url;
            // $description = $request->description;
            $validation = Validator::make($request->all(), [
                'level' => 'required',
                'parent_id' => 'required',
                'name' => 'required|max:100|unique:menus,name',
                'icon' => 'required|max:100',
                'url' => 'required|max:100',
                'description' => 'required|max:250'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            Menu::create($validation);
            return response()->json([
                'status' => true,
                'message' => 'Menu berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
    public function update_menu(Request $request)
    {
        try {
            $id = $request->id;
            $validation = Validator::make($request->all(), [
                'level' => 'required',
                'parent_id' => 'required',
                'name' => 'required|max:100|unique:menus,name',
                'icon' => 'required|max:100',
                'url' => 'required|max:100',
                'description' => 'required|max:250'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            Menu::find($id)->update($validation);
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
    public function delete_menu(Request $request)
    {
        try {
            $id = $request->id;
            $check = Menu::find($id);
            if (!$check) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
