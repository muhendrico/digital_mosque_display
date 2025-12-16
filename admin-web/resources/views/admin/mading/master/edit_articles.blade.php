@extends('layouts.admin')

@section('title', 'Edit Artikel')

@section('content')
{{-- 1. CSS SUMMERNOTE (Tetap di sini tidak masalah) --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* RESET WARNA PAKSA */
    .form-control, .form-select, input[type="text"], textarea {
        background-color: #ffffff !important;
        color: #000000 !important;
        border: 1px solid #ccc !important;
    }
    .note-editor .note-editable { background-color: #fff !important; color: #000 !important; }
    .note-toolbar { background: #f2f2f2 !important; }
    
    /* PREVIEW IMAGE STYLE */
    .image-preview-box {
        width: 100%; height: 250px; border: 2px dashed #999;
        background: #f8f9fa; display: flex; align-items: center; justify-content: center;
        cursor: pointer; position: relative; overflow: hidden;
    }
    .image-preview-box img { width: 100%; height: 100%; object-fit: cover; display: none; }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <form action="{{ route('admin.master.articles.update', $article->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') 
                
                <div class="card shadow" style="background-color: #fff;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-pencil-fill me-2"></i> Edit Artikel
                        </h5>
                    </div>

                    <div class="card-body p-4 text-dark">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Judul Artikel</label>
                                    <input type="text" name="title" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        value="{{ old('title', $article->title) }}" 
                                        required style="color: #000 !important;">

                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Isi Konten</label>
                                    
                                    <textarea id="buffer-content" style="display: none;">{!! old('content', $article->content) !!}</textarea>

                                    <textarea id="summernote" name="content" class="form-control @error('content') is-invalid @enderror"></textarea>

                                    @error('content')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-dark">Gambar Artikel</label>
                                        
                                        <div class="image-preview-box mb-3" onclick="document.getElementById('imageInput').click()">
                                            <div id="placeholderText" class="text-center text-secondary" 
                                                 style="{{ $article->image ? 'display:none;' : '' }}">
                                                <i class="bi bi-cloud-arrow-up display-4"></i>
                                                <div class="mt-2 small">Klik ganti gambar</div>
                                            </div>
                                            <img id="previewImage" src="{{ $article->image_url ?? '#' }}" 
                                                 alt="Preview" style="{{ $article->image ? 'display:block;' : 'display:none;' }}">
                                        </div>
                                        
                                        <input type="file" name="image" id="imageInput" class="d-none @error('image') is-invalid @enderror" accept="image/*" onchange="previewFile(this)">
                                        
                                        @error('image')
                                            <div class="text-danger small mt-2 d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                        <small class="text-muted">*Biarkan kosong jika tidak ingin mengganti gambar.</small>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary fw-bold">Simpan Perubahan</button>
                                    <a href="{{ route('admin.master.articles.index') }}" class="btn btn-outline-secondary">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi Summernote
        $('#summernote').summernote({
            placeholder: 'Tulis isi artikel disini...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'help']]
            ]
        });

        // 2. INJECT CONTENT DARI BUFFER
        var content = $('#buffer-content').val(); 
        $('#summernote').summernote('code', content);
    });

    // Fungsi Preview Gambar
    function previewFile(input) {
        var file = input.files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                document.getElementById("previewImage").src = reader.result;
                document.getElementById("previewImage").style.display = "block";
                document.getElementById("placeholderText").style.display = "none";
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection