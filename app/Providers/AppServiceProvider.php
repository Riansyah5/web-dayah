<?php

namespace App\Providers;

use App\Models\AcademicYear;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share variabel $globalActiveYear ke SEMUA view blade
        // Kita pakai View Composer agar query hanya jalan jika view dirender
        View::composer('*', function ($view) {
            // Cache sederhana bisa ditambahkan nanti, sekarang query langsung saja
            $activeYear = AcademicYear::where('is_active', true)->first();
            $view->with('globalActiveYear', $activeYear);
        });

        // Superadmin otomatis lolos semua pengecekan Gate (termasuk @can di Blade)
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Superadmin') ? true : null;
        });
    }
}
