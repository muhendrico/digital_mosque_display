@extends('layouts.admin')

@section('title', 'Kelola Slider')

@section('content')

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
</style>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="container-fluid">
    <div class="row">
        {{-- BAGIAN KIRI: FORM UPLOAD --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active fw-bold" data-bs-toggle="pill" href="#tab-media">
                                <i class="bi bi-images"></i> Media
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" data-bs-toggle="pill" href="#tab-infaq">
                                <i class="bi bi-chat-quote"></i> Infaq
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" data-bs-toggle="pill" href="#tab-article">
                                <i class="bi bi-book"></i> Artikel
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content">
                        
                        {{-- 1. TAB MEDIA --}}
                        <div class="tab-pane fade show active" id="tab-media">
                            <form action="{{ route('admin.master.sliders.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="slider_type" value="media">
                                
                                <div class="mb-3">
                                    <label class="form-label text-white-50">Judul Slide</label>
                                    <input type="text" name="title" class="form-control" placeholder="Contoh: Kegiatan Jumat" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white-50">File (Gambar/Video)</label>
                                    <div class="input-group">
                                        <input type="file" name="image" class="form-control" required>
                                        <label class="input-group-text"><i class="bi bi-folder2-open"></i></label>
                                    </div>
                                    <div class="form-text text-white-50 small mt-1">JPG/PNG/MP4. Max 20MB.</div>
                                </div>

                                {{-- PR #4: INTERVAL INPUT --}}
                                <div class="mb-4">
                                    <label class="form-label text-white-50">Durasi Tayang</label>
                                    <div class="input-group">
                                        <input type="number" name="interval" class="form-control" value="5000" min="1000" step="500" required>
                                        <span class="input-group-text text-white">ms</span>
                                    </div>
                                    <div class="form-text text-white-50 small mt-1">Default 5000 (5 detik).</div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-upload"></i> Upload Media</button>
                            </form>
                        </div>

                        {{-- 2. TAB INFAQ --}}
                        <div class="tab-pane fade" id="tab-infaq">
                            <form action="{{ route('admin.master.sliders.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <i class="bi bi-info-circle"></i> Jika kosong, pakai <strong>Default</strong>.
                                    </div>
                                </div>

                                {{-- PR #4: INTERVAL INPUT --}}
                                <div class="mb-4">
                                    <label class="form-label text-white-50">Durasi Tayang</label>
                                    <div class="input-group">
                                        <input type="number" name="interval" class="form-control" value="5000" min="1000" step="500" required>
                                        <span class="input-group-text text-white">ms</span>
                                    </div>
                                    <div class="form-text text-white-50 small mt-1">Disarankan 10000+ untuk teks panjang.</div>
                                </div>

                                <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-save"></i> Simpan Motivasi</button>
                            </form>
                        </div>

                        {{-- 3. TAB ARTICLE --}}
                        <div class="tab-pane fade" id="tab-article">
                            <form action="{{ route('admin.master.sliders.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="slider_type" value="article">

                                <div class="mb-3">
                                    <label class="form-label text-white-50">Pilih Artikel</label>
                                    <select name="article_id" class="form-select" id="articleSelector" required>
                                        <option value="">-- Pilih Judul Artikel --</option>
                                        @foreach($articles as $article)
                                            <option value="{{ $article['id'] }}" data-title="{{ $article['title'] }}">
                                                {{ $article['title'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-white-50">Judul Slide (Opsional)</label>
                                    <input type="text" name="title" id="articleSlideTitle" class="form-control" placeholder="Otomatis mengikuti judul artikel">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label text-white-50">Gambar Artikel</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                    <div class="form-text text-white-50 small mt-1">
                                        <i class="bi bi-magic"></i> Biarkan kosong untuk pakai <strong>Gambar Artikel</strong>.
                                    </div>
                                </div>

                                {{-- PR #4: INTERVAL INPUT --}}
                                <div class="mb-4">
                                    <label class="form-label text-white-50">Durasi Tayang</label>
                                    <div class="input-group">
                                        <input type="number" name="interval" class="form-control" value="5000" min="1000" step="500" required>
                                        <span class="input-group-text text-white">ms</span>
                                    </div>
                                    <div class="form-text text-white-50 small mt-1">Default 5000 (5 detik).</div>
                                </div>

                                <button type="submit" class="btn btn-warning w-100 fw-bold text-dark">
                                    <i class="bi bi-save"></i> Pasang Slide Artikel
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: TABEL DAFTAR SLIDER --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-list-check me-2"></i> Daftar Slider Aktif</h5>
                    <span class="badge bg-info text-dark">{{ $sliders->count() }} Slide</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="25%">Preview</th>
                                    <th>Judul & Info</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sliders as $slider)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="ratio ratio-16x9 rounded overflow-hidden border border-secondary position-relative">
                                            
                                            @if($slider->type == 'video')
                                                <video 
                                                    src="{{ $slider->image_url }}"
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
                                                <img src="{{ $slider->image_url }}" alt="Slider" style="object-fit: cover;">
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $slider->title ?? '(Tanpa Judul)' }}</div>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            {{-- Badge Tipe --}}
                                            <div class="small text-white-50 text-uppercase" style="font-size: 0.75rem;">
                                                @if($slider->type == 'infaq')
                                                    <span class="text-success"><i class="bi bi-chat-quote"></i> Motivasi Infaq</span>
                                                @elseif($slider->type == 'article')
                                                    <span class="text-warning"><i class="bi bi-book"></i> Artikel / Kajian</span>
                                                @elseif($slider->type == 'video')
                                                    <span class="text-danger"><i class="bi bi-camera-video"></i> Video MP4</span>
                                                @else
                                                    <span class="text-info"><i class="bi bi-image"></i> Gambar Media</span>
                                                @endif
                                            </div>

                                            <span class="badge bg-secondary bg-opacity-50 border border-secondary" title="Durasi Tayang">
                                                <i class="bi bi-stopwatch"></i> {{ $slider->interval / 1000 }}s
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning btn-edit-slider" 
                                                    data-id="{{ $slider->id }}" 
                                                    title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                    
                                            <form action="{{ route('admin.master.sliders.destroy', $slider->id) }}" method="POST" onsubmit="return confirm('Yakin hapus media ini?');">
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

{{-- MODAL EDIT SLIDER --}}
{{-- MODAL EDIT SLIDER --}}
<div class="modal fade" id="modalEditSlider" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        {{-- GANTI WARNA DI SINI: background diubah dari Ungu (#2D0B42) menjadi Biru Gelap (#102C57) --}}
        <div class="modal-content" style="background: #102C57; color: white; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 0 20px rgba(0,0,0,0.5);">
            
            <div class="modal-header border-secondary">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i> Edit Slider</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formEditSlider" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="edit_id" name="id">

                <div class="modal-body">
                    
                    {{-- 1. Judul --}}
                    <div class="mb-3">
                        <label class="form-label text-white-50">Judul Slide</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required 
                               style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2); color: white;">
                    </div>

                    {{-- 2. Interval --}}
                    <div class="mb-3">
                        <label class="form-label text-white-50">Durasi Tayang (ms)</label>
                        <input type="number" class="form-control" id="edit_interval" name="interval" min="1000" step="500" required
                               style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2); color: white;">
                        <div class="form-text text-white-50 small">1000 ms = 1 Detik.</div>
                    </div>

                    {{-- 3. Quote (Khusus Infaq) --}}
                    <div class="mb-3 d-none" id="container_quote">
                        <label class="form-label text-white-50">Kutipan / Motivasi Infaq</label>
                        <textarea class="form-control" id="edit_quote" name="quote" rows="4"
                                  style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2); color: white;"></textarea>
                    </div>

                    {{-- 4. Gambar --}}
                    <div class="mb-3">
                        <label class="form-label text-white-50">Ganti Gambar/Video (Opsional)</label>
                        <input type="file" class="form-control" id="edit_image" name="image"
                               style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.2); color: white;">
                        <div class="form-text text-white-50 small">Biarkan kosong jika tidak ingin mengubah gambar.</div>
                    </div>

                    {{-- Preview --}}
                    <div class="mb-3 text-center">
                        <label class="d-block text-white-50 mb-2">Preview Saat Ini:</label>
                        <img id="preview_edit_image" src="" alt="Preview" style="max-height: 150px; border-radius: 8px; border: 1px solid #555; display:none;">
                        <p id="no_preview_text" class="text-white-50 small" style="display:none;">(Tidak ada preview tersedia)</p>
                    </div>

                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="btnUpdateSlider">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        
        // 1. KLIK TOMBOL EDIT (Buka Modal & Ambil Data)
        $('.btn-edit-slider').click(function() {
            var id = $(this).data('id');
            var url = "{{ route('admin.master.sliders.show', ':id') }}".replace(':id', id);

            // Tampilkan Loading (Opsional)
            $('#btnUpdateSlider').text('Loading...').attr('disabled', true);

            $.get(url, function(response) {
                var slider = response.data

                $('#edit_id').val(slider.id);
                $('#edit_title').val(slider.title);
                $('#edit_interval').val(slider.interval);
                
                // Logic Gambar Preview
                if(slider.image_url) {
                    $('#preview_edit_image').attr('src', slider.image_url).show();
                } else {
                    $('#preview_edit_image').hide();
                }

                if(slider.type === 'infaq') {
                    $('#container_quote').removeClass('d-none');
                    var quoteText = "";
                    if(slider.extra_data) {
                        try {
                            var extra = typeof slider.extra_data === 'string' ? JSON.parse(slider.extra_data) : slider.extra_data;
                            quoteText = extra.quote || "";
                        } catch(e) { quoteText = ""; }
                    }
                    $('#edit_quote').val(quoteText);
                } else {
                    $('#container_quote').addClass('d-none');
                }

                $('#modalEditSlider').modal('show');
                $('#btnUpdateSlider').html('<i class="bi bi-save"></i> Simpan Perubahan').attr('disabled', false);
            }).fail(function() {
                alert('Gagal mengambil data slider.');
            });
        });


        // 2. KLIK TOMBOL SIMPAN (Submit Form via AJAX)
        $('#formEditSlider').submit(function(e) {
            e.preventDefault();
            
            var id = $('#edit_id').val();
            var url = "{{ route('admin.master.sliders.update', ':id') }}".replace(':id', id);
            var formData = new FormData(this); // Pakai FormData biar bisa upload file

            $('#btnUpdateSlider').text('Menyimpan...').attr('disabled', true);

            $.ajax({
                url: url,
                type: 'POST', // Method POST tapi di dalamnya ada _method: PUT
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    // Tutup Modal
                    $('#modalEditSlider').modal('hide');
                    
                    // Reset Form
                    $('#formEditSlider')[0].reset();

                    var row = $('.btn-edit-slider[data-id="' + id + '"]').closest('tr');
                    
                    row.find('.fw-bold').text(response.data.title);
                    
                    var intervalSec = response.data.interval / 1000;
                    row.find('.badge.bg-secondary').html('<i class="bi bi-stopwatch"></i> ' + intervalSec + 's');

                    if(response.data.image_url) {
                         row.find('img').attr('src', response.data.image_url);
                    }

                    alert('Data berhasil diperbarui!');
                    
                    $('#btnUpdateSlider').html('<i class="bi bi-save"></i> Simpan Perubahan').attr('disabled', false);
                },
                error: function(xhr) {
                    alert('Gagal menyimpan perubahan. Periksa inputan anda.');
                    $('#btnUpdateSlider').html('<i class="bi bi-save"></i> Simpan Perubahan').attr('disabled', false);
                    console.log(xhr.responseText);
                }
            });
        });

    });

    document.addEventListener("DOMContentLoaded", function() {
        var articleSelect = document.getElementById('articleSelector');
        var titleInput = document.getElementById('articleSlideTitle');

        if(articleSelect) {
            articleSelect.addEventListener('change', function() {
                var selectedText = articleSelect.options[articleSelect.selectedIndex].text;
                
                if(this.value !== "") {
                    titleInput.value = selectedText.trim();
                } else {
                    titleInput.value = "";
                }
            });
        }
    });
</script>
@endsection