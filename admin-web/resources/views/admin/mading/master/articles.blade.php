@extends('layouts.admin')

@section('title', 'Daftar Artikel')

@section('css')
{{-- Load DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<style>
    .text-white-50 { color: rgba(255, 255, 255, 0.7) !important; font-size: 0.9rem; font-weight: 500; }
    .form-control, .form-select {
        background-color: rgba(0, 0, 0, 0.3) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #fff !important;
    }
    .form-control:focus, .form-select:focus {
        background-color: rgba(0, 0, 0, 0.5) !important;
        border-color: #00BFFF !important;
        box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
    }
    .form-select option { background-color: #333; color: white; } /* Fix dropdown color on options */
    .input-group-text {
        background-color: rgba(0, 0, 0, 0.4) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white;
    }
    .card-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0,0,0,0.2);
        padding: 15px 20px;
    }
    /* Tab Styles */
    .nav-tabs .nav-link { color: rgba(255,255,255,0.7); border: none; }
    .nav-tabs .nav-link.active { background-color: rgba(255,255,255,0.1); color: #fff; border-radius: 10px; }
    .nav-tabs .nav-link:hover { color: #fff; }
    
    .table { color: #fff; margin-bottom: 0; }
    .table thead th { background-color: rgba(0,0,0,0.4); border-bottom: 2px solid rgba(255,255,255,0.1); }
    .table td { border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.1); }

    /* 1. GLASS CARD STYLE (Transparan & Blur) */
    .glass-card {
        background: rgba(255, 255, 255, 0.1); /* Transparan Putih 10% */
        backdrop-filter: blur(10px);          /* Efek Blur di belakang */
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2); /* Garis tipis */
        border-radius: 15px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        color: #fff !important; /* Semua teks jadi putih */
        overflow: hidden;
    }

    /* 2. HEADER CARD */
    .glass-header {
        background: rgba(0, 0, 0, 0.1); /* Sedikit lebih gelap */
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* 3. Button Tambah */
    /* 3. Button Tambah (REVISI FINAL) */
    .btn-3d-cyan {
        /* WARNA GRADIENT (Paksa Muncul) */
        background: linear-gradient(180deg, #0dcaf0 0%, #0991ad 100%) !important;
        background-color: #0dcaf0 !important;
        
        /* TEKS */
        color: #000 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        white-space: nowrap; /* Agar teks tidak turun baris */
        
        /* BENTUK */
        border: none !important;
        border-radius: 50px !important;
        padding: 8px 24px !important;
        
        /* POSISI (PENTING: JANGAN PAKAI BLOCK/100%) */
        display: inline-flex !important; 
        width: auto !important;          
        align-items: center;
        justify-content: center;
        
        /* EFEK 3D */
        box-shadow: 
            0 4px 0 #07768c,               
            0 5px 10px rgba(0,0,0,0.2),    
            inset 0 1px 0 rgba(255,255,255,0.4) !important;
            
        transition: all 0.1s ease !important;
        transform: translateY(0);
    }

    .btn-3d-cyan:hover {
        background: linear-gradient(180deg, #3dd5f3 0%, #0dcaf0 100%) !important;
        color: #000 !important;
        transform: translateY(-2px); 
        box-shadow: 
            0 6px 0 #07768c,
            0 7px 15px rgba(13, 202, 240, 0.4),
            inset 0 1px 0 rgba(255,255,255,0.4) !important;
    }

    .btn-3d-cyan:active {
        transform: translateY(4px) !important; 
        box-shadow: 
            0 0 0 #07768c, 
            inset 0 3px 5px rgba(0,0,0,0.2) !important;
    }
    
    /* Perbaikan Icon */
    .btn-3d-cyan i {
        margin-right: 5px;
        font-size: 1rem;
        font-weight: bold;
    }

    /* 5. INPUT SEARCH & SELECT (Transparan) */
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        background: rgba(255, 255, 255, 0.1) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: #fff !important;
        border-radius: 5px;
        padding: 5px 10px;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        background: rgba(255, 255, 255, 0.2) !important;
        border-color: #0dcaf0 !important;
        outline: none;
        box-shadow: none;
    }
    /* Placeholder warna putih pudar */
    ::placeholder { color: rgba(255, 255, 255, 0.6) !important; opacity: 1; }

    /* 6. PAGINATION (Tombol Halaman) */
    .page-link {
        background: transparent !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: #fff !important;
    }
    .page-item.active .page-link {
        background-color: #0dcaf0 !important;
        border-color: #0dcaf0 !important;
        color: #000 !important;
        font-weight: bold;
    }
    .page-item.disabled .page-link {
        color: rgba(255,255,255,0.4) !important;
    }

    /* 7. INFO TEXT (Showing 1 to 10...) */
    .dataTables_info { color: rgba(255,255,255,0.7) !important; }

    /* 8. ACTION BUTTONS (Kotak Kuning & Merah) */
    .btn-action {
        width: 35px;
        height: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: 0.2s;
        border: none;
    }
    .btn-edit { background: #ffc107; color: #000; } /* Kuning */
    .btn-edit:hover { background: #ffca2c; }
    
    .btn-delete { background: #dc3545; color: #fff; } /* Merah */
    .btn-delete:hover { background: #bb2d3b; }

    /* SLUG BOX */
    .slug-box {
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        font-family: monospace;
        font-size: 0.9rem;
    }

    /* Style untuk Link Judul */
    .title-link {
        text-decoration: none;
        color: #ffffff !important; /* Default Putih */
        transition: all 0.2s ease;
        display: block; /* Agar area klik luas */
    }
    .title-link:hover {
        color: #0dcaf0 !important; /* Berubah Cyan saat hover */
        text-shadow: 0 0 10px rgba(13, 202, 240, 0.5); /* Efek Glow */
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    
    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert alert-success border-0 text-white shadow-sm mb-4" role="alert" style="background: rgba(25, 135, 84, 0.8);">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-list-check me-2"></i> Daftar Artikel</h5>
                    <span class="badge bg-info text-dark">{{ $articles->count() }} Artikel</span>
                    <a href="{{ route('admin.master.articles.create') }}" class="btn btn-3d-cyan">
                        <i class="bi bi-plus-lg me-1"></i> Buat Artikel
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="25%" class="text-center">Preview</th>
                                    <th>Judul Artikel</th>
                                    <th>URL</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="ratio ratio-16x9 rounded overflow-hidden border border-secondary position-relative">
                                            <img src="{{ $article->image_url ?? 'https://via.placeholder.com/300' }}" alt="Article" style="object-fit: cover;">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 p-2 rounded" style="background: rgba(255,255,255,0.2);">
                                                <i class="bi bi-file-text fs-4 text-white"></i>
                                            </div>
                                            <div>
                                                <a href="{{ route('public.article.show', $article->slug) }}" class="title-link" target="_blank">
                                                    <div class="fw-bold fs-6">{{ $article->title }}</div>
                                                </a>
                                                <small style="color: rgba(255,255,255,0.7);">
                                                    <i class="bi bi-calendar3 me-1"></i> 
                                                    {{ \Carbon\Carbon::parse($article->created_at)->format('d M Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="slug-box" style="max-width: 250px;">
                                            /{{ $article->slug }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.master.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Yakin hapus media ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-white-50">
                                        <i class="bi bi-collection-play display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada article yang diupload.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tableArtikel').DataTable({
            // CONFIG BAHASA MANUAL (Agar tidak error CORS)
            language: {
                "search": "Search:",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "paginate": { "next": "Next", "previous": "Previous" },
                "emptyTable": "Belum ada artikel"
            }
        });
    });
</script>
@endsection