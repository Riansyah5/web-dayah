<?php

namespace App\Http\Middleware;

use App\Models\AppSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika mode maintenance tidak aktif, lanjutkan secara normal
        if (!AppSetting::isMaintenance()) {
            // Jika user secara sengaja membuka halaman /maintenance saat sistem normal, arahkan ke dashboard/home
            if ($request->routeIs('maintenance.screen')) {
                return redirect()->route(Auth::check() ? 'dashboard' : 'login');
            }
            return $next($request);
        }

        // Mode maintenance SEDANG AKTIF:
        
        // 1. Jika pengguna sudah login dan merupakan Superadmin, izinkan akses penuh
        if (Auth::check() && Auth::user()->hasRole('Superadmin')) {
            return $next($request);
        }

        // 2. Daftar rute pengecualian (whitelist) agar proses login/logout dan halaman maintenance tetap dapat diakses
        $whitelistRoutes = [
            'maintenance.screen',
            'login',
            'login.process',
            'logout',
        ];

        foreach ($whitelistRoutes as $routeName) {
            if ($request->routeIs($routeName)) {
                return $next($request);
            }
        }

        // 3. Pengecualian berkas aset statis
        if ($request->is('assets/*') || $request->is('storage/*') || $request->is('vendor/*') || $request->is('up')) {
            return $next($request);
        }

        // 4. Jika request berupa API / AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'maintenance',
                'message' => 'Sistem sedang dalam proses pemeliharaan. Akses dibatasi untuk Superadmin.',
            ], 503);
        }

        // 5. Arahkan semua akun lain / pengunjung ke halaman pemeliharaan
        return redirect()->route('maintenance.screen');
    }
}
