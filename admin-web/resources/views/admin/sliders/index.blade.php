@extends('layouts.admin')

@section('title', 'Kelola Slider')

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="bi bi-cloud-upload me-2"></i> Upload Gambar Baru</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label text-white-50">Judul (Opsional)</label>
                            <input type="text" name="title" class="form-control" placeholder="Contoh: Kajian Subuh">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-white-50">Pilih Gambar/Video</label>
                            <div class="input-group">
                                <input type="file" name="image" class="form-control" id="inputGroupFile02" required>
                                <label class="input-group-text" for="inputGroupFile02"><i class="bi bi-image"></i></label>
                            </div>
                            <div class="form-text text-white-50 small mt-2">
                                <i class="bi bi-info-circle"></i> Format: JPG/PNG. Rasio 16:9. Max 2MB.
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-upload me-2"></i> Upload Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-list-check me-2"></i> Daftar Slider Aktif</h5>
                    <span class="badge bg-info text-dark">{{ $sliders->count() }} Gambar</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="20%">Preview</th>
                                    <th>Judul</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sliders as $slider)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="ratio ratio-16x9 rounded overflow-hidden border border-secondary">
                                            <img src="{{ asset('storage/' . $slider->image_path) }}" alt="Slider" style="object-fit: cover;">
                                        </div>
                                    </td>
                                    <td class="fw-bold">{{ $slider->title ?? '-' }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.sliders.delete', $slider->id) }}" method="POST" onsubmit="return confirm('Yakin hapus gambar ini?');">
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
                                        <i class="bi bi-images display-6 d-block mb-2 opacity-50"></i>
                                        Belum ada slider yang diupload.
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
    /* Style Tabel Transparan */
    .table { color: #fff; margin-bottom: 0; }
    .table thead th { background-color: rgba(0,0,0,0.4); border-bottom: 2px solid rgba(255,255,255,0.1); }
    .table td { border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.1); }
</style>
@endsection