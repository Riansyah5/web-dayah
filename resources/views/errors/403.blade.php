<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        .container {
            text-align: center;
            padding: 2rem;
            position: relative;
        }

        /* Efek Glow di Background */
        .glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            z-index: -1;
        }

        h1 {
            font-size: 8rem;
            margin: 0;
            font-weight: 800;
            background: linear-gradient(to right, #6366f1, #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        h2 {
            font-size: 1.5rem;
            margin-top: 0.5rem;
            color: #94a3b8;
        }

        p {
            max-width: 400px;
            margin: 1.5rem auto;
            line-height: 1.6;
            color: #64748b;
        }

        .btn-back {
            display: inline-block;
            margin-top: 2rem;
            padding: 0.8rem 2rem;
            background-color: #6366f1;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
            border: none;
            cursor: pointer;
        }

        .btn-back:hover {
            background-color: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(99, 102, 241, 0.4);
        }

        .illustration {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <div class="glow"></div>

    <div class="container">
        <div class="illustration">🚫</div>
        <h1>403</h1>
        <h2>Waduh, Area Terlarang!</h2>
        @if(isset($exception) && $exception->getMessage())
            <p>{{ $exception->getMessage() }}</p>
        @elseif(!empty($message))
            <p>{{ $message }}</p>
        @else
            <p>Maaf banget, tapi kamu nggak punya izin buat masuk ke halaman ini. Mungkin kamu salah jalan atau butuh akses khusus.</p>
        @endif
        <button onclick="goBack()" class="btn-back">
            Kembali ke Sebelumnya
        </button>
    </div>

    <script>
        function goBack() {
            // Mengecek apakah ada riwayat halaman sebelumnya
            if (document.referrer !== "") {
                window.history.back();
            } else {
                // Jika tidak ada riwayat (langsung buka link), arahkan ke homepage
                window.location.href = "/";
            }
        }
    </script>

</body>
</html>