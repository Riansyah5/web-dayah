<!doctype html>
<html lang="en">
<!-- [Head] start -->

<head>
  <title>Login</title>
  <!-- [Meta] -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description"
    content="Berry is trending dashboard template made using Bootstrap 5 design framework. Berry is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
  <meta name="keywords"
    content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
  <meta name="author" content="codedthemes" />

  <!-- [Favicon] icon -->
  <link rel="icon" href="{{ asset('assets/images/logo_dayah.png') }}" type="image/x-icon" />
  <!-- [Google Font] Family -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    id="main-font-link" />
  <!-- [phosphor Icons] https://phosphoricons.com/ -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/phosphor/duotone/style.css') }}" />
  <!-- [Tabler Icons] https://tablericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" />
  <!-- [Feather Icons] https://feathericons.com -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" />
  <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" />
  <!-- [Material Icons] https://fonts.google.com/icons -->
  <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" />
  <!-- [Template CSS Files] -->
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" />
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" />

</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body>
  <!-- [ Pre-loader ] start -->
  {{-- <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div> --}}
  <!-- [ Pre-loader ] End -->

  <div class="auth-main">
    <div class="auth-wrapper v3">
      <div class="auth-form">
        <div class="card my-5">
          <div class="card-body">
            <a href="#" class="d-flex justify-content-center">
              <img src="{{ asset('assets/images/logo_dayah.png') }}" alt="image" class="img-fluid brand-logo" style="width: 20%;"/>
            </a>
            <div class="row">
              <div class="d-flex justify-content-center">
                <div class="auth-header">
                  <h2 class="text-secondary mt-5"><b>السلام عليكم ورحمة الله وبركاته</b></h2>
                  {{-- <p class="f-16 mt-2">Enter your credentials to continue</p> --}}
                </div>
              </div>
            </div>
            {{-- <div class="d-grid">
              <button type="button" class="btn mt-2 bg-light-primary bg-light text-muted">
                <img src="{{ asset('assets/images/authentication/google-icon.svg') }}" alt="image" />Sign In With
                Google
              </button>
            </div> --}}
            <div class="saprator mt-3">
              <span class=""><i class="ti ti-lock-access"></i></span>
            </div>
            <h5 class="my-4 d-flex justify-content-center">Sign in with Email address / Username</h5>
            <form action="{{ route('login.process') }}" method="POST">
              @csrf
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="login" name="login" value="{{ old('login') }}" placeholder="Email address / Username" />
                <label for="login">Email address / Username</label>
              </div>
              <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                <label for="password">Password</label>
                <i class="ti ti-eye position-absolute top-50 end-0 translate-middle-y me-3 fs-4 text-secondary" 
                   id="togglePassword" style="cursor: pointer; z-index: 10;"></i>
              </div>
              <div class="d-flex mt-1 justify-content-between">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" id="customCheckc1" name="remember" />
                  <label class="form-check-label text-muted" for="customCheckc1">Remember me</label>
                </div>
                <h5 class="text-secondary" style="cursor: pointer;"
                  onclick="Swal.fire({
                    icon: 'info',
                    title: 'Lupa Password?',
                    text: 'Silakan hubungi admin apabila lupa password.'
                  })">
                  Forgot Password?</h5>
              </div>
              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-secondary">Sign In</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- [ Main Content ] end -->
  
  {{-- sweetAlert --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @error('nonaktif')
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        html: '<h2>{{ $message }}</h2> Silahkan hubungi admin.',
    });
    </script>
  @enderror
  @error('login')
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        html: '<h2>{{ $message }}</h2>',
    });
    </script>
  @enderror
  <!-- Required Js -->
  <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/icon/custom-font.js') }}"></script>
  <script src="{{ asset('assets/js/script.js') }}"></script>
  <script src="{{ asset('assets/js/theme.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>

  <script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
      // toggle the type attribute
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      
      // toggle the eye slash icon
      this.classList.toggle('ti-eye-off');
      this.classList.toggle('ti-eye');
    });
  </script>

  <script>
    layout_change('light');
  </script>

  <script>
    font_change('Roboto');
  </script>

  <script>
    change_box_container('false');
  </script>

  <script>
    layout_caption_change('true');
  </script>

  <script>
    layout_rtl_change('false');
  </script>

  <script>
    preset_change('preset-1');
  </script>


</body>
<!-- [Body] end -->

</html>
