<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Supplier extends Model
{
    protected $guarded = [];
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public static function front_table(){
        $company_id = Auth::user()->company_id;
        return Supplier::with('company:id,company_name')
        ->where('company_id',$company_id)
        ->latest();
    }
}
