<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        return view('masters.suppliers.index', [
            'sidebar' => $sidebar
        ]);
    }
    public function front_table(Request $request)
    {
        if ($request->ajax()) {
            $data = Supplier::front_table();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('company_name', function ($row) {
                    return $row->company->company_name;
                })
                ->addColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        $data = '<span class="badge badge-primary">Active</span>';
                    } else {
                        $data = '<span class="badge badge-warning">Non active</span>';
                    }
                    return $data;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary" onclick="editSupplier(' . $row->id . ')"><i class="ti ti-edit"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="deleteSupplier(' . $row->id . ')"><i class="ti ti-trash"></i></button>
                    ';
                })
                ->rawColumns(['company_name','status','action'])
                ->make(true);
        }
    }
}
