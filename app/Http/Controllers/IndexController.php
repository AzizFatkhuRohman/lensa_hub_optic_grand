<?php

namespace App\Http\Controllers;

use App\Models\MenuAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        $user_id = Auth::user()->id;
        $sidebar = MenuAccess::sidebar($user_id);
        // dd($sidebar);
        return view('index', [
            'sidebar' => $sidebar
        ]);
    }
}
