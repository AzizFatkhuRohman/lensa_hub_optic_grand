<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(){
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        return view('masters.customers.index', [
            'sidebar' => $sidebar
        ]);
    }
    public function front_table(Request $request){
        if ($request->ajax()) {
            $data = Customer::front_table();
        }
    }
}
