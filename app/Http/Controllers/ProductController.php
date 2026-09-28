<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\MenuAccess;
use App\Models\Product;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * =========================================================
     * INDEX
     * =========================================================
     */
    public function index()
    {
        $user_id = Auth::id();

        $sidebar = MenuAccess::sidebar($user_id);

        $categories = Category::where('status', 'active')
            ->orderBy('category_name')
            ->get();

        $brands = Brand::where('status', 'active')
            ->orderBy('brand_name')
            ->get();

        $types = Type::where('status', 'active')
            ->orderBy('type_name')
            ->get();

        $colors = Color::where('status', 'active')
            ->orderBy('color_name')
            ->get();

        $units = Unit::where('status', 'active')
            ->orderBy('unit_name')
            ->get();

        return view('masters.products.index', [
            'sidebar' => $sidebar,
            'categories' => $categories,
            'brands' => $brands,
            'types' => $types,
            'colors' => $colors,
            'units' => $units,
        ]);
    }

    /**
     * =========================================================
     * FRONT TABLE
     * =========================================================
     */
    public function front_table(Request $request)
    {
        if (! $request->ajax()) {
            abort(404);
        }

        $data = Product::with([
            'category',
            'brand',
            'type',
            'color',
            'unit',
        ])->latest();

        return DataTables::of($data)

            ->addIndexColumn()

            /*
             * -----------------------------------------------------
             * KATEGORI
             * -----------------------------------------------------
             */
            ->addColumn('category_name', function ($row) {
                return $row->category
                    ? $row->category->category_name
                    : '-';
            })

            /*
             * -----------------------------------------------------
             * BRAND
             * -----------------------------------------------------
             */
            ->addColumn('brand_name', function ($row) {
                return $row->brand
                    ? $row->brand->brand_name
                    : '-';
            })

            /*
             * -----------------------------------------------------
             * TYPE
             * -----------------------------------------------------
             */
            ->addColumn('type_name', function ($row) {
                return $row->type
                    ? $row->type->type_name
                    : '-';
            })

            /*
             * -----------------------------------------------------
             * COLOR
             * -----------------------------------------------------
             */
            ->addColumn('color_name', function ($row) {
                return $row->color
                    ? $row->color->color_name
                    : '-';
            })

            /*
             * -----------------------------------------------------
             * UNIT
             * -----------------------------------------------------
             */
            ->addColumn('unit_name', function ($row) {
                return $row->unit
                    ? $row->unit->unit_name
                    : '-';
            })

            /*
             * -----------------------------------------------------
             * HARGA BELI
             * -----------------------------------------------------
             */
            ->editColumn('purchase_price', function ($row) {
                return number_format(
                    $row->purchase_price,
                    0,
                    ',',
                    '.'
                );
            })

            /*
             * -----------------------------------------------------
             * HARGA JUAL
             * -----------------------------------------------------
             */
            ->editColumn('selling_price', function ($row) {
                return number_format(
                    $row->selling_price,
                    0,
                    ',',
                    '.'
                );
            })

            /*
             * -----------------------------------------------------
             * STATUS
             * -----------------------------------------------------
             */
            ->editColumn('status', function ($row) {

                if ($row->status === 'active') {
                    return '<span class="badge bg-success-subtle text-success">
                                Aktif
                            </span>';
                }

                return '<span class="badge bg-danger-subtle text-danger">
                            Tidak Aktif
                        </span>';
            })

            /*
             * -----------------------------------------------------
             * ACTION
             * -----------------------------------------------------
             */
            ->addColumn('action', function ($row) {

                return '
                    <div class="d-flex gap-1">

                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            onclick="editProduct('.$row->id.')"
                            title="Edit">

                            <i class="ti ti-edit"></i>

                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-danger"
                            onclick="deleteProduct('.$row->id.')"
                            title="Hapus">

                            <i class="ti ti-trash"></i>

                        </button>

                    </div>
                ';
            })

            ->rawColumns([
                'status',
                'action',
            ])

            ->make(true);
    }

    /**
     * =========================================================
     * SHOW
     * =========================================================
     */
    public function show(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validation->errors(),
            ], 422);
        }

        $product = Product::with([
            'category',
            'brand',
            'type',
            'color',
            'unit',
        ])->find($request->id);

        if (! $product) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product,
        ]);
    }

    /**
     * =========================================================
     * STORE
     * =========================================================
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'product_code' => [
                'required',
                'string',
                'max:50',
                'unique:products,product_code',
            ],

            'product_name' => [
                'required',
                'string',
                'max:100',
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'brand_id' => [
                'required',
                'integer',
                'exists:brands,id',
            ],

            'type_id' => [
                'required',
                'integer',
                'exists:types,id',
            ],

            'color_id' => [
                'required',
                'integer',
                'exists:colors,id',
            ],

            'unit_id' => [
                'required',
                'integer',
                'exists:units,id',
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);

        if ($validation->fails()) {

            return response()->json([
                'status' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $product = Product::create([

            'product_code' => $request->product_code,

            'product_name' => $request->product_name,

            'category_id' => $request->category_id,

            'brand_id' => $request->brand_id,

            'type_id' => $request->type_id,

            'color_id' => $request->color_id,

            'unit_id' => $request->unit_id,

            'purchase_price' => $request->purchase_price,

            'selling_price' => $request->selling_price,

            'minimum_stock' => $request->minimum_stock,

            'status' => $request->status,

            'created_by' => Auth::id(),

        ]);

        return response()->json([

            'status' => true,

            'message' => 'Produk berhasil ditambahkan.',

            'data' => $product,

        ]);
    }

    /**
     * =========================================================
     * UPDATE
     * =========================================================
     */
    public function update(Request $request)
    {
        $validation = Validator::make($request->all(), [

            'id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'product_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'product_code')
                    ->ignore($request->id),
            ],

            'product_name' => [
                'required',
                'string',
                'max:100',
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'brand_id' => [
                'required',
                'integer',
                'exists:brands,id',
            ],

            'type_id' => [
                'required',
                'integer',
                'exists:types,id',
            ],

            'color_id' => [
                'required',
                'integer',
                'exists:colors,id',
            ],

            'unit_id' => [
                'required',
                'integer',
                'exists:units,id',
            ],

            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

        ]);

        if ($validation->fails()) {

            return response()->json([
                'status' => false,
                'errors' => $validation->errors(),
            ], 422);
        }

        $product = Product::find($request->id);

        if (! $product) {

            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $product->update([

            'product_code' => $request->product_code,

            'product_name' => $request->product_name,

            'category_id' => $request->category_id,

            'brand_id' => $request->brand_id,

            'type_id' => $request->type_id,

            'color_id' => $request->color_id,

            'unit_id' => $request->unit_id,

            'purchase_price' => $request->purchase_price,

            'selling_price' => $request->selling_price,

            'minimum_stock' => $request->minimum_stock,

            'status' => $request->status,

            'updated_by' => Auth::id(),

        ]);

        return response()->json([

            'status' => true,

            'message' => 'Produk berhasil diperbarui.',

            'data' => $product,

        ]);
    }

    /**
     * =========================================================
     * DELETE
     * =========================================================
     */
    public function delete(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => [
                'required',
                'integer',
                'exists:products,id',
            ],
        ]);

        if ($validation->fails()) {

            return response()->json([
                'status' => false,
                'message' => $validation->errors(),
            ], 422);
        }

        $product = Product::find($request->id);

        if (! $product) {

            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $product->delete();

        return response()->json([

            'status' => true,

            'message' => 'Produk berhasil dihapus.',

        ]);
    }
}
