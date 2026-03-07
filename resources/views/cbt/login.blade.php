<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Ujian | CBT Pesantren</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: none;
        }
        .login-header {
            background-color: #0d6efd; /* Ganti dengan warna tema pesantren */
            color: white;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            padding: 2rem 1.5rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="login-header">
            <i class="bi bi-laptop fs-1 mb-2 d-block"></i>
            <h4 class="fw-bold mb-0">PORTAL UJIAN (CBT)</h4>
            <small class="opacity-75">Silakan login menggunakan akun ujian</small>
        </div>
        
        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger py-2 small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cbt.login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold small text-muted">Nomor Peserta / Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Contoh: CBT-001" value="{{ old('username') }}" required autofocus autocomplete="off">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">PIN Ujian / Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0 ps-0" placeholder="Masukkan PIN" required autocomplete="off">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow-sm">
                    MULAI LOGIN <i class="bi bi-arrow-right-circle ms-1"></i>
                </button>
            </form>
        </div>
        <div class="card-footer bg-white border-0 text-center py-3">
            <small class="text-muted">&copy; {{ date('Y') }} Sistem CBT Pesantren</small>
        </div>
    </div>

</body>
</html>