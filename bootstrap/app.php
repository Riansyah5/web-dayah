<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan Facades Auth ini

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias di sini
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // === PENGATURAN REDIRECT MULTI-AUTH (LARAVEL 11) ===
        
        // 1. Jika user BELUM login (Guest), arahkan berdasarkan prefix URL
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('cbt') || $request->is('cbt/*')) {
                return route('cbt.login');
            }
            return route('login'); // Default ke login admin
        });

        // 2. Jika user SUDAH login, jangan biarkan kembali ke halaman login
        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('cbt')->check()) {
                return route('cbt.dashboard');
            }
            return route('dashboard'); // Default ke dashboard admin
        });
        // ====================================================

        // === TAMBAHKAN PENGECUALIAN CSRF DI SINI ===
        $middleware->validateCsrfTokens(except: [
            'cbt/login',
            'cbt/exam/autosave/*',
            'cbt/exam/finish/*',
            'cbt/exam/heartbeat/*'
        ]);
        // ===========================================
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // --- TAMBAHKAN KODE INI UNTUK MENANGKAP ERROR 403 SPATIE ---
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
            
            // Jika request berupa API atau AJAX (seperti submit form pakai fetch/axios)
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Maaf, Anda tidak memiliki akses untuk tindakan ini.'], 403);
            }

            // Jika request biasa, kembalikan ke dashboard dengan pesan error (Flash Message)
            // return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Anda tidak memiliki izin untuk membuka halaman tersebut.');
            
            // ALTERNATIF: Jika Anda lebih suka menampilkan halaman khusus 403 daripada redirect
            return response()->view('errors.403', [], 403);
        });
        // ------------------------------------------------------------
    })->create();