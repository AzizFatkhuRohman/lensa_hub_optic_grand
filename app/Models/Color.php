<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'color_code',
        'color_name',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
