<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuAccess;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class TypeController extends Controller
{
    public function index(): View
    {
        return view('masters.types.index', [
            'sidebar' => MenuAccess::sidebar(Auth::id()),
            'categories' => Category::query()->orderBy('category_name')->get(),
        ]);
    }

    public function front_table(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            abort(404);
        }

        return DataTables::of(Type::query()->with('category')->latest())
            ->addIndexColumn()
            ->addColumn('category_name', function (Type $type): string {
                return $type->category?->category_name ?? '-';
            })
            ->addColumn('action', function (Type $type): string {
                return '<button type="button" class="btn btn-sm btn-primary" onclick="editType('.$type->id.')" title="Edit"><i class="ti ti-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteType('.$type->id.')" title="Hapus"><i class="ti ti-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show(Request $request): JsonResponse
    {
        $type = Type::with('category')->find($request->id);

        if (! $type) {
            return response()->json([
                'status' => false,
                'message' => 'Tipe produk tidak ditemukan.',
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $type,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateType($request);

        Type::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Type berhasil ditambahkan.',
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $type = Type::find($request->id);

        if (! $type) {
            return response()->json([
                'status' => false,
                'message' => 'Tipe produk tidak ditemukan.',
            ]);
        }

        $validated = $this->validateType($request, $type);

        $type->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Type berhasil diperbarui.',
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $type = Type::find($request->id);

        if (! $type) {
            return response()->json([
                'status' => false,
                'message' => 'Tipe produk tidak ditemukan.',
            ]);
        }

        if ($type->products()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Type tidak dapat dihapus karena masih digunakan oleh produk.',
            ], 422);
        }

        $type->delete();

        return response()->json([
            'status' => true,
            'message' => 'Type berhasil dihapus.',
        ]);
    }

    /**
     * @return array{type_code: string, type_name: string, category_id: int, description: ?string, status: string}
     */
    private function validateType(Request $request, ?Type $type = null): array
    {
        return Validator::make($request->all(), [
            'type_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('types', 'type_code')->ignore($type?->id),
            ],
            'type_name' => ['required', 'string', 'max:100'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ])->validate();
    }
}
