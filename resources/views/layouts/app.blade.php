<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>@yield('title', 'Web Dayah')</title>
  {{-- head-page-meta --}}
  @include('layouts.head-page-meta')
  {{-- head-css --}}
  @include('layouts.head-css')
  @stack('link') {{-- Untuk LINK spesifik halaman --}}
  @stack('styles') {{-- Untuk CSS spesifik halaman --}}

</head>
<body>
  {{-- sidebar --}}
  @include('layouts.sidebar')
  {{-- topbar --}}
  @include('layouts.topbar')
  
  {{-- main content --}}
  <div class="pc-container">
    <div class="pc-content">
      @if(\App\Models\AppSetting::isMaintenance() && Auth::check() && Auth::user()->hasRole('Superadmin'))
        <div class="alert alert-danger shadow-sm d-flex flex-wrap align-items-center justify-content-between gap-2 mb-4 border-2" role="alert">
          <div class="d-flex align-items-center gap-2">
            <i class="ti ti-alert-triangle fs-3 text-danger"></i>
            <div>
              <strong class="text-danger">PERHATIAN: Mode Pemeliharaan (Maintenance) Sedang AKTIF!</strong>
              <div class="small text-muted">Aplikasi saat ini dinonaktifkan untuk seluruh pengguna lain. Hanya Superadmin yang dapat mengakses sistem.</div>
            </div>
          </div>
          <a href="{{ route('system.maintenance-mode.index') }}" class="btn btn-sm btn-danger fw-semibold">
            <i class="ti ti-settings me-1"></i> Kelola Pemeliharaan
          </a>
        </div>
      @endif

      @yield('content')
    </div>
  </div>

  {{-- footer-block --}}
  @include('layouts.footer-block')
  
  {{-- scripts --}}
  @include('layouts.footer-js')
  @stack('scripts') {{-- Untuk JavaScript spesifik halaman --}}
</body>
</html>