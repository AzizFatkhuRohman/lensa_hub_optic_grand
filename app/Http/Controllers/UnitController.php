<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class UnitController extends Controller
{
    public function index(): View
    {
        return view('masters.units.index', [
            'sidebar' => MenuAccess::sidebar(Auth::id()),
        ]);
    }

    public function frontTable(Request $request): JsonResponse
    {
        if (! $request->ajax()) {
            abort(404);
        }

        return DataTables::of(Unit::query()->latest())
            ->addIndexColumn()
            ->addColumn('action', function (Unit $unit): string {
                return '<button type="button" class="btn btn-sm btn-primary" onclick="editUnit('.$unit->id.')" title="Edit"><i class="ti ti-edit"></i></button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteUnit('.$unit->id.')" title="Hapus"><i class="ti ti-trash"></i></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create(): View
    {
        return $this->index();
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateUnit($request);

        Unit::create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Unit berhasil ditambahkan.',
        ]);
    }

    public function edit(Unit $unit): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $unit,
        ]);
    }

    public function update(Request $request, Unit $unit): JsonResponse
    {
        $validated = $this->validateUnit($request, $unit);

        $unit->update([
            ...$validated,
            'updated_by' => Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Unit berhasil diperbarui.',
        ]);
    }

    public function destroy(Unit $unit): JsonResponse
    {
        if ($unit->products()->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Unit tidak dapat dihapus karena masih digunakan oleh produk.',
            ], 422);
        }

        $unit->delete();

        return response()->json([
            'status' => true,
            'message' => 'Unit berhasil dihapus.',
        ]);
    }

    /**
     * @return array{unit_code: string, unit_name: string, description: ?string, status: string}
     */
    private function validateUnit(Request $request, ?Unit $unit = null): array
    {
        return Validator::make($request->all(), [
            'unit_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'unit_code')->ignore($unit?->id),
            ],
            'unit_name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ])->validate();
    }
}
