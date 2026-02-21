<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // 🔥 Superadmin otomatis lolos semua role
        if ($user->role === 'Superadmin') {
            return $next($request);
        }

        // Cek apakah role user ada di dalam daftar role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika tidak punya hak akses (403 Forbidden)
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}