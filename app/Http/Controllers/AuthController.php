<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            return back()
                ->with('login_error', 'Akun tidak ditemukan.')
                ->onlyInput('username');
        }

        if (!$user->is_active) {
            return back()
                ->with('login_error', 'Akun sudah tidak aktif.')
                ->onlyInput('username');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()
            ->with('login_error', 'Username atau password salah.')
            ->onlyInput('username');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('login');
    }
}
