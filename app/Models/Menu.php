<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function accesses()
    {
        return $this->hasMany(MenuAccess::class, 'menu_id');
    }
    public static function front_table()
    {
        return Menu::orderBy('name','desc');
    }
}
