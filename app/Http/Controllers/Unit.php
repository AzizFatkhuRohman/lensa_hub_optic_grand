<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_code',
        'unit_name',
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
