<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Masjid Raya</title>
    
    {{-- Font & Icon --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8; /* Abu-abu sangat muda (Background Web) */
            color: #333;
            margin: 0; padding: 0;
        }

        /* Navbar Sederhana (Opsional) */
        .public-navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 0;
            margin-bottom: 40px;
        }
        .navbar-brand {
            font-weight: 800;
            color: #17a2b8; /* Cyan Branding */
            font-size: 1.5rem;
        }
        
        /* Footer Sederhana */
        .public-footer {
            background: #fff;
            padding: 30px 0;
            margin-top: 60px;
            text-align: center;
            border-top: 1px solid #eee;
            color: #777;
        }
    </style>
    @yield('css')
</head>
<body>

    <nav class="public-navbar">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="#" class="navbar-brand">
                <i class="bi bi-building me-2"></i> 
                {{-- Panggil Dinamis --}}
                {{ $settings['nama_masjid'] ?? 'Masjid Raya' }}
            </a>
            
            {{-- Tombol Login Admin (Opsional) --}}
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="bi bi-person-lock"></i> Login
            </a>
        </div>
    </nav>

    {{-- Content Area --}}
    <div class="container">
        @yield('content')
    </div>

    {{-- Footer --}}
    <footer class="public-footer">
        <div class="container">
            <small>
                &copy; {{ date('Y') }} 
                {{-- Panggil Dinamis --}}
                {{ $settings['nama_masjid'] ?? 'Masjid Raya' }}. 
                {{ $settings['footer_text'] ?? 'Semua konten dilindungi.' }}
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>