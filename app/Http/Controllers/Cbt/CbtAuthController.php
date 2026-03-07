<?php

namespace App\Http\Controllers\Cbt;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CbtAuthController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        // Jika sudah login di guard 'cbt', langsung arahkan ke dashboard CBT
        if (Auth::guard('cbt')->check()) {
            return redirect()->route('cbt.dashboard');
        }
        return view('cbt.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string', // Di UI kita bisa tulis "PIN Ujian"
        ]);

        // Coba login menggunakan guard 'cbt'
        if (Auth::guard('cbt')->attempt($credentials, $request->boolean('remember'))) {
            
            // Cek apakah akun aktif
            if (!Auth::guard('cbt')->user()->is_active) {
                Auth::guard('cbt')->logout();
                return back()->withErrors([
                    'username' => 'Akun Anda dinonaktifkan. Silakan hubungi pengawas/admin.',
                ])->onlyInput('username');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('cbt.dashboard'));
        }

        // Jika gagal login
        return back()->withErrors([
            'username' => 'Username atau PIN yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::guard('cbt')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('cbt.login');
    }
}