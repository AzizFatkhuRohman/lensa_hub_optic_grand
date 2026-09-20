<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Validation\Rule;

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
                    if ($row->is_active == '1') {
                        return '<span class="badge bg-primary">Active</span>';
                    }

                    return '<span class="badge bg-warning">Non active</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary" onclick="editSupplier(' . $row->id . ')"><i class="ti ti-edit"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="deleteSupplier(' . $row->id . ')"><i class="ti ti-trash"></i></button>
                    ';
                })
                ->rawColumns(['company_name', 'status', 'action'])
                ->make(true);
        }
    }
    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'code' => 'required|max:20|unique:suppliers,code',
                'name' => 'required|max:200|unique:suppliers,name',
                'npwp' => 'nullable|max:30',
                'phone' => 'nullable|numeric',
                'email' => 'nullable|max:50',
                'country' => 'nullable|max:100',
                'province' => 'nullable|max:100',
                'city' => 'nullable|max:100',
                'district' => 'nullable|max:100',
                'postal_code' => 'nullable|max:10',
                'is_active' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages()
                ]);
            }
            $data = [
                'company_id' => $request->company_id,
                'code' => $request->code,
                'name' => $request->name,
                'npwp' => $request->npwp,
                'phone' => $request->phone,
                'email' => $request->email,
                'country' => $request->country,
                'province' => $request->province,
                'city' => $request->city,
                'district' => $request->district,
                'postal_code' => $request->postal_code,
                'is_active' => $request->is_active,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
            ];
            Supplier::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Supplier berhasil ditambahkan'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function show(Request $request)
    {
        try {
            $supplier = Supplier::with('company:id,company_name')->where('company_id', Auth::user()->company_id)
                ->find($request->id);

            if (! $supplier) {
                return response()->json([
                    'status' => false,
                    'message' => 'Supplier tidak ditemukan',
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => $supplier,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function update(Request $request)
    {
        try {
            $supplier = Supplier::where('company_id', Auth::user()->company_id)
                ->find($request->id);

            if (! $supplier) {
                return response()->json([
                    'status' => false,
                    'message' => 'Supplier tidak ditemukan',
                ]);
            }

            $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'code' => [
                    'required',
                    'max:20',
                    Rule::unique('suppliers', 'code')->ignore($supplier->id),
                ],
                'name' => [
                    'required',
                    'max:200',
                    Rule::unique('suppliers', 'name')->ignore($supplier->id),
                ],
                'npwp' => 'nullable|max:30',
                'phone' => 'nullable|numeric',
                'email' => 'nullable|email|max:50',
                'country' => 'nullable|max:100',
                'province' => 'nullable|max:100',
                'city' => 'nullable|max:100',
                'district' => 'nullable|max:100',
                'postal_code' => 'nullable|max:10',
                'is_active' => 'required',
            ]);

            if ($validation->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validation->messages(),
                ]);
            }

            $data = $validation->validated();
            $data['updated_by'] = Auth::id();

            $supplier->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Supplier berhasil diubah',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
    public function delete(Request $request)
    {
        try {
            $supplier = Supplier::find($request->id);

            if (! $supplier) {
                return response()->json([
                    'status' => false,
                    'message' => 'Supplier tidak ditemukan',
                ]);
            }

            $supplier->delete();

            return response()->json([
                'status' => true,
                'message' => 'Supplier berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
