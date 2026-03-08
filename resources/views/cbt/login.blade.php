<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Ujian | CBT Pesantren Modern</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563eb;
            --accent-color: #0f172a;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        /* Dekorasi Background Bulatan Halus */
        body::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            top: -50px;
            right: -50px;
            z-index: -1;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            transition: transform 0.3s ease;
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: var(--primary-color);
            color: white;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.4);
        }

        .login-title {
            color: var(--accent-color);
            font-weight: 700;
            letter-spacing: -0.025em;
            margin-bottom: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .input-group {
            background-color: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            background-color: #fff;
        }

        .input-group-text {
            background-color: transparent;
            border: none;
            padding-left: 1rem;
            color: #94a3b8;
        }

        .form-control {
            background-color: transparent;
            border: none;
            padding: 0.75rem 1rem 0.75rem 0;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
            background-color: transparent;
        }

        .btn-login {
            background-color: var(--primary-color);
            border: none;
            padding: 0.8rem;
            border-radius: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .alert-custom {
            border-radius: 0.75rem;
            border: none;
            background-color: #fef2f2;
            color: #991b1b;
            font-size: 0.85rem;
        }

        .footer-text {
            font-size: 0.8rem;
            color: #94a3b8;
            margin-top: 2rem;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <i class="bi bi-shield-check fs-2"></i>
        </div>

        <div class="text-center mb-4">
            <h3 class="login-title">Portal CBT</h3>
            <p class="text-muted small">Sistem Ujian Pesantren Terintegrasi</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-custom mb-4">
                <div class="d-flex">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <ul class="mb-0 ps-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('cbt.login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">ID Peserta / Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="CBT-XXXXX" value="{{ old('username') }}" required autofocus autocomplete="off">
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between">
                    <label class="form-label">PIN Keamanan</label>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="off">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                Masuk ke Ujian <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} <strong>Lembaga Pendidikan Pesantren</strong><br>
            <span class="opacity-75">Versi 2.4.0</span>
        </div>
    </div>

</body>
</html>