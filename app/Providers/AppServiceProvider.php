<?php

namespace App\Providers;

use App\Models\AcademicCalendar;
use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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

        // Mengirim data upcomingEvents ke semua view yang meload header
        // Ganti '*' dengan nama view header Anda jika memungkinkan, misal 'layouts.header'
        View::composer('*', function ($view) {
            $upcomingEvents = AcademicCalendar::whereDate('start_date', '<=', Carbon::today()->addDays(7))
                ->whereDate('start_date', '>=', Carbon::today())
                ->orderBy('start_date', 'asc')
                ->get();

            $view->with('upcomingEvents', $upcomingEvents);
        });
    }
}
