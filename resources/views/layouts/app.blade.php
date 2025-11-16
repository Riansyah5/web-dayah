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