<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Kost Sejahtera</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; min-height: 100vh; display: flex; }
        .left-panel {
            flex: 1;
            background: linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)),
                        url('https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800&h=900&fit=crop') center/cover;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 48px;
            color: #fff;
        }
        .left-panel h1 { font-size: clamp(2rem, 3.5vw, 2.8rem); font-weight: 900; line-height: 1.2; margin-bottom: 12px; }
        .left-panel p  { color: rgba(255,255,255,.7); font-size: 1rem; line-height: 1.7; max-width: 380px; }
        .right-panel {
            width: 520px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 56px;
        }
        .back-link { color: #6B7280; text-decoration: none; font-size: .875rem; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 40px; }
        .back-link:hover { color: #111827; }
        .brand-icon-lg { width: 64px; height: 64px; background: #E6B800; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 16px; }
        .form-label-custom { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; color: #6B7280; display: block; margin-bottom: 6px; }
        .input-wrapper { display: flex; align-items: center; gap: 10px; border: 1.5px solid #E5E7EB; border-radius: 8px; padding: 0 14px; background: #F9FAFB; transition: border-color .15s; }
        .input-wrapper:focus-within { border-color: #E6B800; box-shadow: 0 0 0 3px rgba(230,184,0,.15); }
        .input-wrapper input { border: none; outline: none; flex: 1; font-size: .875rem; padding: 11px 0; background: transparent; font-family: inherit; }
        .btn-masuk { background: #E6B800; color: #111827; border: none; border-radius: 12px; padding: 14px; font-weight: 800; font-size: 1rem; width: 100%; cursor: pointer; transition: opacity .15s; letter-spacing: .02em; }
        .btn-masuk:hover { opacity: .88; }
        .demo-box { background: #F3F4F6; border-radius: 8px; padding: 12px 16px; font-size: .8rem; color: #6B7280; text-align: center; margin-top: 20px; }
        @media (max-width: 768px) { .left-panel { display: none; } .right-panel { width: 100%; padding: 32px 24px; } }
    </style>
</head>
<body>
    <div class="left-panel">
        <h1>Selamat Datang<br><span style="color:#E6B800">Owner Hebat!</span></h1>
        <p>Kelola bisnis kost Anda dengan mudah, pantau pendapatan secara realtime, dan nikmati kemudahan teknologi.</p>
        <div style="margin-top:40px;color:rgba(255,255,255,.4);font-size:.8rem;">© {{ date('Y') }} Sistem Manajemen Kost Profesional</div>
    </div>

    <div class="right-panel">
        <a href="{{ route('home') }}" class="back-link">← Kembali ke Website</a>

        <div style="text-align:center;margin-bottom:36px;">
            <div class="brand-icon-lg">🏠</div>
            <h2 style="font-weight:900;font-size:1.75rem;margin-bottom:6px;">Admin Login</h2>
            <p style="color:#6B7280;margin:0;">Masuk untuk mengelola kost Anda</p>
        </div>

        @if($errors->any())
        <div style="background:#FEE2E2;color:#991B1B;border-radius:8px;padding:12px 16px;font-size:.875rem;margin-bottom:16px;">
            {{ $errors->first('email') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div style="margin-bottom:16px;">
                <label class="form-label-custom">Alamat Email</label>
                <div class="input-wrapper">
                    <span>✉️</span>
                    <input type="email" name="email" value="{{ old('email', 'admin@kostsejahtera.com') }}" required autocomplete="email">
                </div>
            </div>
            <div style="margin-bottom:28px;">
                <label class="form-label-custom">Password</label>
                <div class="input-wrapper">
                    <span>🔒</span>
                    <input type="password" name="password" required autocomplete="current-password">
                </div>
            </div>
            <button type="submit" class="btn-masuk">MASUK SISTEM →</button>
        </form>

        <div style="text-align:center;margin-top:20px;color:#6B7280;font-size:.875rem;">
            Lupa password? Hubungi <span style="color:#E6B800;font-weight:700;cursor:pointer;">Developer</span>
        </div>

        <div class="demo-box">
            Demo: <strong>admin@kostsejahtera.com</strong> / <strong>admin123</strong>
        </div>
    </div>
</body>
</html>
