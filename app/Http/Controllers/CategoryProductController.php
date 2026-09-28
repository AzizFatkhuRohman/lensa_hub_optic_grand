<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class CategoryProductController extends Controller
{
    public function index(): View
    {
        $sidebar = MenuAccess::sidebar(Auth::id());

        return view('masters.category_products.index', [
            'sidebar' => $sidebar,
        ]);
    }

    public function front_table(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            abort(404);
        }

        return DataTables::of(Category::query()->latest())
            ->addIndexColumn()
            ->addColumn('action', function (Category $row): string {
                return '
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        onclick="editCategoryProduct('.$row->id.')"
                        title="Edit">
                        <i class="ti ti-edit"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-danger"
                        onclick="deleteCategoryProduct('.$row->id.')"
                        title="Hapus">
                        <i class="ti ti-trash"></i>
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Request $request): JsonResponse
    {
        $category = Category::find($request->id);

        if (! $category) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori produk tidak ditemukan',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $category,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'category_code' => [
                'required',
                'string',
                'max:50',
                'unique:categories,category_code',
            ],

            'category_name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validation->messages(),
            ], 422);
        }

        Category::create([
            'category_code' => $validation->validated()['category_code'],
            'category_name' => $validation->validated()['category_name'],
            'description' => $validation->validated()['description'] ?? null,
            'status' => 'active',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kategori produk berhasil dibuat',
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $category = Category::find($request->id);

        if (! $category) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori produk tidak ditemukan',
            ]);
        }

        $validation = Validator::make($request->all(), [
            'category_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categories', 'category_code')
                    ->ignore($category->id),
            ],

            'category_name' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validation->messages(),
            ], 422);
        }

        $category->update([
            'category_code' => $validation->validated()['category_code'],
            'category_name' => $validation->validated()['category_name'],
            'description' => $validation->validated()['description'] ?? null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Kategori produk berhasil diubah',
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $category = Category::find($request->id);

        if (! $category) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori produk tidak ditemukan',
            ]);
        }

        // Jangan hapus jika masih digunakan oleh product
        if ($category->products()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Kategori produk berhasil dihapus',
        ]);
    }
}
