<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
            'recaptcha_token' => 'required',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'recaptcha_token.required' => 'Verifikasi keamanan gagal.',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Verifikasi reCAPTCHA v3
    |--------------------------------------------------------------------------
    */

        $captcha = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $request->recaptcha_token,
                'remoteip' => $request->ip(),
            ]
        );

        $captchaData = $captcha->json();

        /*
    |--------------------------------------------------------------------------
    | Cek CAPTCHA berhasil
    |--------------------------------------------------------------------------
    */

        if (!($captchaData['success'] ?? false)) {
            return back()
                ->with('login_error', 'Verifikasi keamanan gagal. Silakan coba lagi.')
                ->onlyInput('username');
        }

        /*
    |--------------------------------------------------------------------------
    | Cek action
    |--------------------------------------------------------------------------
    */

        if (($captchaData['action'] ?? '') !== 'login') {
            return back()
                ->with('login_error', 'Verifikasi keamanan tidak valid.')
                ->onlyInput('username');
        }

        /*
    |--------------------------------------------------------------------------
    | Cek score
    |--------------------------------------------------------------------------
    */

        $score = $captchaData['score'] ?? 0;

        if ($score < (float) env('RECAPTCHA_MIN_SCORE', 0.5)) {
            return back()
                ->with('login_error', 'Aktivitas mencurigakan terdeteksi. Silakan coba lagi.')
                ->onlyInput('username');
        }

        /*
    |--------------------------------------------------------------------------
    | Cari user
    |--------------------------------------------------------------------------
    */

        $user = User::where('username', $credentials['username'])->first();

        if (!$user) {
            return back()
                ->with('login_error', 'Akun tidak ditemukan.')
                ->onlyInput('username');
        }

        /*
    |--------------------------------------------------------------------------
    | Cek user aktif
    |--------------------------------------------------------------------------
    */

        if (!$user->is_active) {
            return back()
                ->with('login_error', 'Akun sudah tidak aktif.')
                ->onlyInput('username');
        }

        /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {

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
