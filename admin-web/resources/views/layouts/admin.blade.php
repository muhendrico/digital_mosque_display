<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Masjid</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to bottom right, #5D3FD3 0%, #00BFFF 100%);
            color: #ffffff;
            margin: 0; padding: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            position: fixed; top: 0; left: 0;
            min-height: 100vh;
            background-color: #2236ed; /* Ungu Gelap */
            color: white;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
            padding: 0; box-sizing: border-box;
            width: 250px; flex-shrink: 0;
        }

        .sidebar h4 {
            color: #ffffff; font-weight: 800;
            padding: 2rem 1rem 1rem 1rem;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }

        .sidebar hr { margin: 0 1rem 20px 1rem; border-color: rgba(255, 255, 255, 0.1); }
        .sidebar ul { padding: 0 1rem; list-style: none; margin: 0; }
        .sidebar .nav-item { width: 100%; margin-bottom: 5px; box-sizing: border-box; }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none; padding: 12px 15px;
            transition: background-color 0.3s ease, color 0.3s ease;
            border-radius: 8px; display: flex; align-items: center; width: 100%;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1); color: #ffffff;
        }

        .sidebar .nav-link.active {
            background-color: #00BFFF; /* Deep Sky Blue */
            color: #2D0B42; font-weight: 700;
            box-shadow: 0 2px 10px rgba(0, 191, 255, 0.4);
        }
        
        .sidebar .nav-link.active i { color: #2D0B42; }

        .content-area { margin-left: 250px; flex-grow: 1; padding: 25px; }

        /* Custom Card Style agar transparan */
        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px; color: #ffffff;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .card-header { background-color: rgba(0, 0, 0, 0.2); border-bottom: 1px solid rgba(255, 255, 255, 0.1); }
        .card-title { margin: 0; font-weight: 700; }

        /* Custom Table Style */
        .table { color: #ffffff; border-color: rgba(255, 255, 255, 0.3); }
        .table th, .table td { border-color: rgba(255, 255, 255, 0.3); background-color: transparent; color: white; }
        .table-striped tbody tr:nth-of-type(odd) { background-color: rgba(255, 255, 255, 0.05); }
        .table-hover tbody tr:hover { background-color: rgba(255, 255, 255, 0.15); }

        /* Form Controls (Input) agar terlihat bagus di background gelap */
        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
        }
        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.2);
            color: white; border-color: #00BFFF; box-shadow: none;
        }
        ::placeholder { color: rgba(255, 255, 255, 0.5) !important; }

        /* Buttons */
        .btn-primary { background-color: #00BFFF; border-color: #00BFFF; color: #2D0B42; font-weight: 600; }
        .btn-primary:hover { background-color: #00A3D9; border-color: #00A3D9; }
        .btn-danger { background-color: #dc3545; border-color: #dc3545; color: white; }

        /* --- TAMBAHAN STYLE UNTUK DASHBOARD WIDGET --- */
        
        /* Mengubah kotak statistik menjadi style Glassmorphism */
        .stat-card {
            background: rgba(255, 255, 255, 0.1); /* Transparan */
            border: 1px solid rgba(255, 255, 255, 0.2); /* Border halus */
            border-radius: 20px; /* Sudut sangat membulat */
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, background 0.3s ease;
            backdrop-filter: blur(5px); /* Efek blur di belakangnya */
            color: white;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }

        .stat-card:hover {
            transform: translateY(-5px); /* Efek naik saat dihover */
            background: rgba(255, 255, 255, 0.2); /* Lebih terang saat hover */
            border-color: rgba(255, 255, 255, 0.5);
        }

        .stat-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.3; /* Ikon transparan */
            color: white;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .stat-label {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            font-weight: 600;
        }
        
        /* Warna spesifik untuk variasi (opsional, jika ingin beda warna dikit) */
        .stat-card.purple { background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(157, 80, 255, 0.2)); }
        .stat-card.blue { background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(0, 191, 255, 0.2)); }
        .stat-card.green { background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(46, 204, 113, 0.2)); }
    
        @yield('css');
    </style>
</head>

<body>
    <div class="d-flex">
        <div class="sidebar">
            <h4 class="text-center">Admin Masjid</h4>
            <hr>
            <ul class="nav flex-column">
                
                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('admin.master.settings*') ? 'active' : '' }}"
                        href="{{ route('admin.master.settings.index') }}">
                        <i class="bi bi-gear-fill me-2"></i> Pengaturan
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('admin.master.sliders*') ? 'active' : '' }}"
                        href="{{ route('admin.master.sliders.index') }}">
                        <i class="bi bi-images me-2"></i> Slider Gambar
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('admin.master.articles*') ? 'active' : '' }}"
                        href="{{ route('admin.master.articles.index') }}">
                        <i class="bi bi-book-half me-2"></i> Artikel
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link {{ request()->routeIs('admin.trans.finances*') ? 'active' : '' }}"
                        href="{{ route('admin.trans.finances.index') }}">
                        <i class="bi bi-wallet2 me-2"></i> Laporan Kas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link" href="{{ url('/') }}" target="_blank">
                        <i class="bi bi-tv me-2"></i> Buka Layar TV
                    </a>
                </li>

                <li class="nav-item mt-5 pt-5">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <div class="content-area">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary">
                <h2 style="font-weight: 700; text-shadow: 2px 2px 5px rgba(0,0,0,0.3);">
                    @yield('title', 'Admin Panel')
                </h2>
            </div>

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function () {
            $('.table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
                }
            });
        });

        @yield('js')
    </script>
</body>
</html>