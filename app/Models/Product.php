<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'category_id',
        'brand_id',
        'type_id',
        'color_id',
        'unit_id',
        'purchase_price',
        'selling_price',
        'minimum_stock',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * Relationship to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relationship to Brand
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    /**
     * Relationship to Type
     */
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * Relationship to Color
     */
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    /**
     * Relationship to Unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
}
