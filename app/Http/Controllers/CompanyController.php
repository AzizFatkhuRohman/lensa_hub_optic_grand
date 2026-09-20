<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class CompanyController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        return view('settings.company.index', [
            'sidebar' => $sidebar
        ]);
    }
    public function front_table(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
            <button class="btn btn-sm btn-primary" onclick="editCompany(' . $row->id . ')"><i class="ti ti-edit"></i></button>
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
            $data = Company::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company tidak ditemukan'
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
                'company_name' => 'required|max:100|unique:companies,company_name',
                'address' => 'required|max:255'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            Company::create($validation->validate());
            return response()->json([
                'status' => true,
                'message' => 'Company berhasil dibuat'
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
                'company_name' => [
                    'required',
                    'max:100',
                    Rule::unique('companies', 'company_name')->ignore($id),
                ],
                'address' => 'required|max:255'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            $data = Company::find($id);
            if (!$data) {
                return response()->json([
                    'status' => false,
                    'mesasge' => 'Company tidak ditemukan'
                ]);
            }
            Company::find($id)->update($validation->validate());
            return response()->json([
                'status' => true,
                'message' => 'Company berhasil diubah'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
