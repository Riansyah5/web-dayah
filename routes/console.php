<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
    })->purpose('Display an inspiring quote');
    
// Command ini akan mengecek keterlambatan setiap 10 menit sekali
Schedule::command('permissions:check-late')->everyTenMinutes();