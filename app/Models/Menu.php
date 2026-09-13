<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public function accesses()
    {
        return $this->hasMany(MenuAccess::class, 'menu_id');
    }
}
