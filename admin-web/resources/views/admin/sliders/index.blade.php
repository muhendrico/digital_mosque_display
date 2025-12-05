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
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item"><a class="nav-link active fw-bold" data-bs-toggle="pill" href="#tab-media"><i class="bi bi-images"></i> Media</a></li>
                        <li class="nav-item"><a class="nav-link fw-bold" data-bs-toggle="pill" href="#tab-infaq"><i class="bi bi-chat-quote"></i> Motivasi Infaq</a></li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        
                        <div class="tab-pane fade show active" id="tab-media">
                            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="slider_type" value="media">
                                
                                <div class="mb-3">
                                    <label class="form-label text-white-50">Judul Slide</label>
                                    <input type="text" name="title" class="form-control" placeholder="Contoh: Kegiatan Jumat">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-white-50">File (Gambar/Video)</label>
                                    <div class="input-group">
                                        <input type="file" name="image" class="form-control" required>
                                        <label class="input-group-text"><i class="bi bi-folder2-open"></i></label>
                                    </div>
                                    <div class="form-text text-white-50 small mt-2">JPG/PNG/MP4. Max 20MB.</div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-upload"></i> Upload Media</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="tab-infaq">
                            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="slider_type" value="infaq">

                                <div class="mb-3">
                                    <label class="form-label text-white-50">Judul Kampanye</label>
                                    <input type="text" name="title" class="form-control" placeholder="Cth: Mari Berwakaf" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white-50">Kutipan / Hadist</label>
                                    <textarea name="quote" class="form-control" rows="4" placeholder="Tulislah hadist atau kata mutiara..." required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white-50">Background Gambar (Opsional)</label>
                                    <input type="file" name="bg_image" class="form-control" accept="image/*">
                                    <div class="form-text text-white-50 small mt-1">
                                        <i class="bi bi-info-circle"></i> Jika kosong, akan menggunakan <strong>Background Default</strong>.
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-save"></i> Simpan Motivasi</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-list-check me-2"></i> Daftar Slider Aktif</h5>
                    <span class="badge bg-info text-dark">{{ $sliders->count() }} Media</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="25%">Preview</th>
                                    <th>Judul & Tipe</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sliders as $slider)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="ratio ratio-16x9 rounded overflow-hidden border border-secondary position-relative">
                                            
                                            {{-- LOGIKA PREVIEW GAMBAR/VIDEO --}}
                                            @if($slider->type == 'video')
                                                <video 
                                                    src="{{ asset('storage/' . $slider->image_path) }}" 
                                                    style="object-fit: cover; width: 100%; height: 100%;" 
                                                    muted loop onmouseover="this.play()" onmouseout="this.pause()"
                                                ></video>
                                                <div class="position-absolute top-0 end-0 m-1">
                                                    <span class="badge bg-danger bg-opacity-75"><i class="bi bi-play-fill"></i> Video</span>
                                                </div>

                                            @elseif($slider->image_path === 'USE_DEFAULT_IMAGE' || $slider->image_path === 'default')
                                                <img src="{{ asset('default-slide.jpg') }}" alt="Default" style="object-fit: cover;">
                                                <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white text-center small py-1">
                                                    Default Background
                                                </div>

                                            @else
                                                <img src="{{ asset('storage/' . $slider->image_path) }}" alt="Slider" style="object-fit: cover;">
                                            @endif

                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $slider->title ?? '(Tanpa Judul)' }}</div>
                                        <div class="small text-white-50 text-uppercase mt-1" style="font-size: 0.75rem;">
                                            @if($slider->type == 'infaq')
                                                <span class="text-success"><i class="bi bi-chat-quote"></i> Motivasi Infaq</span>
                                            @elseif($slider->type == 'video')
                                                <span class="text-warning"><i class="bi bi-camera-video"></i> Video MP4</span>
                                            @else
                                                <span class="text-info"><i class="bi bi-image"></i> Gambar</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.sliders.delete', $slider->id) }}" method="POST" onsubmit="return confirm('Yakin hapus media ini?');">
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
    /* Tab Styles */
    .nav-tabs .nav-link { color: rgba(255,255,255,0.7); border: none; }
    .nav-tabs .nav-link.active { background-color: rgba(255,255,255,0.1); color: #fff; border-radius: 10px; }
    .nav-tabs .nav-link:hover { color: #fff; }
    
    .table { color: #fff; margin-bottom: 0; }
    .table thead th { background-color: rgba(0,0,0,0.4); border-bottom: 2px solid rgba(255,255,255,0.1); }
    .table td { border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.1); }
</style>
@endsection