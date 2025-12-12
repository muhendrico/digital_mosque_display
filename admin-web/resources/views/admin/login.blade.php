<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Masjid Raya</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h3 { font-weight: 800; color: #333; }
        .form-control {
            border-radius: 10px;
            padding: 12px;
            border: 1px solid #ddd;
            background: #f8f9fa;
        }
        .form-control:focus { border-color: #0d6efd; box-shadow: 0 0 0 4px rgba(13,110,253,0.1); }
        .btn-login {
            background: linear-gradient(90deg, #0d6efd, #0a58ca);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            font-size: 1rem;
            width: 100%;
            transition: 0.3s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3); }
        .alert-danger { border-radius: 10px; font-size: 0.9rem; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <h3>Admin Login</h3>
            <p class="text-muted">Masuk untuk mengelola Mading Masjid</p>
        </div>

        {{-- Alert Error --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label fw-bold small text-muted">EMAIL ADDRESS</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="user@email.com" required autofocus>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold small text-muted">PASSWORD</label>
                <input type="password" name="password" class="form-control" placeholder="password anda" required>
            </div>

            <button type="submit" class="btn btn-primary btn-login">Masuk Dashboard</button>
            
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-decoration-none text-muted small">
                    &larr; Kembali ke Layar Utama
                </a>
            </div>
        </form>
    </div>

</body>
</html>