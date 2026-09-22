<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);

        return view('masters.customers.index', [
            'sidebar' => $sidebar,
        ]);
    }

    public function front_table(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::front_table();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('company_name', function ($row) {
                    return $row->company?->company_name;
                })
                ->addColumn('status', function ($row) {
                    if ((int) $row->is_active === 1) {
                        return '<span class="badge bg-primary">Active</span>';
                    }

                    return '<span class="badge bg-warning">Non active</span>';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-primary" onclick="editCustomer(' . $row->id . ')"><i class="ti ti-edit"></i></button>
                        <button class="btn btn-sm btn-danger" onclick="deleteCustomer(' . $row->id . ')"><i class="ti ti-trash"></i></button>
                    ';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'customer_type' => 'required|max:50',
                'code' => 'required|max:20|unique:customers,code',
                'name' => 'required|max:200',
                'brith_date' => 'nullable|date',
                'nik' => 'nullable|max:30',
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
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();

            Customer::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Customer berhasil ditambahkan',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function show(Request $request)
    {
        try {
            $customer = Customer::with('company:id,company_name')
                ->where('company_id', Auth::user()->company_id)
                ->find($request->id);

            if (! $customer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer tidak ditemukan',
                ]);
            }

            return response()->json([
                'status' => true,
                'data' => $customer,
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
            $customer = Customer::where('company_id', Auth::user()->company_id)
                ->find($request->id);

            if (! $customer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer tidak ditemukan',
                ]);
            }

            $validation = Validator::make($request->all(), [
                'company_id' => 'required',
                'customer_type' => 'required|max:50',
                'code' => [
                    'required',
                    'max:20',
                    Rule::unique('customers', 'code')->ignore($customer->id),
                ],
                'name' => 'required|max:200',
                'brith_date' => 'nullable|date',
                'nik' => 'nullable|max:30',
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

            $customer->update($data);

            return response()->json([
                'status' => true,
                'message' => 'Customer berhasil diubah',
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
            $customer = Customer::where('company_id', Auth::user()->company_id)
                ->find($request->id);

            if (! $customer) {
                return response()->json([
                    'status' => false,
                    'message' => 'Customer tidak ditemukan',
                ]);
            }

            $customer->update([
                'is_active' => '0',
                'updated_by' => Auth::id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Customer berhasil dinonaktifkan',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ]);
        }
    }
}
