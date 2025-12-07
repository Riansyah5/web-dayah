<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentukan login pakai email atau username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        // Attempt Login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Cek status
            if (Auth::user()->status !== 'Aktif') {
                Auth::logout();
                return back()->withErrors([
                    'nonaktif' => 'Akun Anda dinonaktifkan.'
                ]);
            }

            $request->session()->regenerate();
            $total = Pegawai::count();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login' => 'Email/Username atau password salah.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
