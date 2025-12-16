@extends('layouts.admin')

@section('title', 'Daftar Artikel')

@section('css')
<style>
    /* Style Tambahan Khusus Halaman Ini */
    .text-white-50 { color: rgba(255, 255, 255, 0.7) !important; font-size: 0.9rem; font-weight: 500; }
    
    /* Tombol Tambah 3D */
    .btn-3d-cyan {
        background: linear-gradient(180deg, #0dcaf0 0%, #0991ad 100%) !important;
        background-color: #0dcaf0 !important;
        color: #000 !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        font-size: 0.85rem;
        border: none !important;
        border-radius: 50px !important;
        padding: 8px 24px !important;
        display: inline-flex !important; 
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 0 #07768c, 0 5px 10px rgba(0,0,0,0.2);
        transition: all 0.1s ease !important;
    }
    .btn-3d-cyan:hover { transform: translateY(-2px); }
    .btn-3d-cyan:active { transform: translateY(4px) !important; box-shadow: 0 0 0 #07768c !important; }

    /* Action Buttons */
    .btn-action { width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; }
    .btn-edit { background: #ffc107; color: #000; }
    .btn-edit:hover { background: #ffca2c; }
    
    /* Slug Box */
    .slug-box { background: rgba(255, 255, 255, 0.15); color: #fff; padding: 5px 10px; border-radius: 5px; font-family: monospace; font-size: 0.9rem; }
    .title-link { text-decoration: none; color: #ffffff !important; display: block; }
    .title-link:hover { color: #0dcaf0 !important; text-shadow: 0 0 10px rgba(13, 202, 240, 0.5); }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    
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
                    <div class="table-responsive p-3"> <table id="tableArtikel" class="table table-hover align-middle mb-0 w-100">
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
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.master.articles.edit', $article->id) }}" class="btn btn-action btn-edit" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                    
                                            <form action="{{ route('admin.master.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Yakin hapus media ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-white-50">
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
<script>
    $(document).ready(function () {
        $('#tableArtikel').DataTable({
            language: {
                "emptyTable": "Tidak ada data yang tersedia",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(disaring dari _MAX_ total data)",
                "lengthMenu": "Tampilkan _MENU_ data",
                "loadingRecords": "Sedang memuat...",
                "processing": "Sedang memproses...",
                "search": "Cari:",
                "zeroRecords": "Tidak ditemukan data yang sesuai",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Lanjut",
                    "previous": "Mundur"
                }
            }
        });
    });
</script>
@endsection