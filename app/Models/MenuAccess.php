<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuAccess extends Model
{
    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public static function sidebar($user_id)
    {
        $menuIds = MenuAccess::where('user_id', $user_id)
            ->pluck('menu_id')
            ->toArray();

        // Menu yang memiliki akses
        $menus = Menu::whereIn('id', $menuIds)->get();

        // Parent level 2 dari menu yang memiliki akses
        $parentIds = $menus
            ->pluck('parent_id')
            ->filter()
            ->unique();

        // Parent level 1
        $grandParentIds = Menu::whereIn('id', $parentIds)
            ->pluck('parent_id')
            ->filter()
            ->unique();

        $allIds = collect($menuIds)
            ->merge($parentIds)
            ->merge($grandParentIds)
            ->unique();

        return Menu::whereIn('id', $allIds)
            ->orderByRaw('COALESCE(parent_id, 0)')
            ->orderBy('id')
            ->get();
    }
    public static function front_table($user_id)
    {
        return MenuAccess::with('menu')->where('user_id', $user_id)->latest();
    }
}
