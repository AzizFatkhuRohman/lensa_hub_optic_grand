<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public function accesses()
    {
        return $this->hasMany(MenuAccess::class, 'menu_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
    public static function parent_show($level, $search)
    {
        if ($level == 1) {
            return collect();
        }
        $parentLevel = $level - 1;
        return Menu::where('level', $parentLevel)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
    }
}
