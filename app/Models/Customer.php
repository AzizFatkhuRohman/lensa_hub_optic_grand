<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public static function front_table()
    {
        return Customer::with('company:id,company_name')->latest();
    }
}
