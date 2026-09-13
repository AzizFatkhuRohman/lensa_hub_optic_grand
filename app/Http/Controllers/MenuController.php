<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::query()
            ->orderBy('level')
            ->orderBy('parent_id')
            ->orderBy('name')
            ->get();

        return view('settings.menus.index', [
            'menus' => $menus,
            'parentMenus' => $menus->whereIn('level', [1, 2]),
            'sidebar' => MenuAccess::sidebar(Auth::id()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'level' => ['required', 'integer', 'in:1,2,3'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id'],
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        if ((int) $validated['level'] === 1) {
            $validated['parent_id'] = null;
        }

        $menu = Menu::create($validated);

        MenuAccess::firstOrCreate([
            'user_id' => Auth::id(),
            'menu_id' => $menu->id,
        ]);

        return to_route('settings.menus.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'level' => ['required', 'integer', 'in:1,2,3'],
            'parent_id' => ['nullable', 'integer', 'exists:menus,id', 'not_in:' . $menu->id],
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        if ((int) $validated['level'] === 1) {
            $validated['parent_id'] = null;
        }

        $menu->update($validated);

        return to_route('settings.menus.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if (Menu::where('parent_id', $menu->id)->exists()) {
            return to_route('settings.menus.index')
                ->with('error', 'Menu tidak dapat dihapus karena masih memiliki submenu.');
        }

        MenuAccess::where('menu_id', $menu->id)->delete();
        $menu->delete();

        return to_route('settings.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}
