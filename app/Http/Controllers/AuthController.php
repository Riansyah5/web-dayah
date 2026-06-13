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

        // Attempt Login secara eksplisit menggunakan guard 'web'
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            
            // Cek status menggunakan guard 'web'
            if (Auth::guard('web')->user()->status !== 'Aktif') {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'nonaktif' => 'Akun Anda dinonaktifkan.'
                ]);
            }

            $request->session()->regenerate();
            
            // Opsional: Baris ini sepertinya tidak dipakai di bawahnya, 
            // bisa dihapus jika memang tidak diteruskan ke view/session.
            $total = Pegawai::count(); 

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'login' => 'Email/Username atau password salah.'
        ]);
    }

    public function logout(Request $request)
    {
        // Logout secara eksplisit untuk guard 'web'
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}