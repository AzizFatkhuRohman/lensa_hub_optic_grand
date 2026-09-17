<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MenuAccessController extends Controller
{
    public function front_table(Request $request)
    {
        $user_id = $request->user_id;
        if ($request->ajax()) {
            $data = MenuAccess::front_table($user_id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
            <button class="btn btn-sm btn-primary">Edit</button>
            <button class="btn btn-sm btn-danger">Delete</button>
        ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
