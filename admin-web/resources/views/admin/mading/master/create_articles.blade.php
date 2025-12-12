@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru')

@section('content')
{{-- 1. CSS LANGSUNG DI SINI (Supaya pasti terbaca) --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* RESET WARNA PAKSA: Agar input terlihat di background putih */
    .form-control, .form-select, input[type="text"], textarea {
        background-color: #ffffff !important;
        color: #000000 !important;
        border: 1px solid #ccc !important;
    }
    
    /* Perbaikan tampilan Summernote */
    .note-editor .note-editable {
        background-color: #fff !important;
        color: #000 !important;
        text-align: left !important;
    }
    .note-toolbar {
        background: #f2f2f2 !important;
    }
    .note-btn {
        color: #333 !important;
        background-color: #fff !important;
    }

    /* Style Preview Upload */
    .image-preview-box {
        width: 100%;
        height: 250px;
        border: 2px dashed #999;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .image-preview-box:hover {
        border-color: #007bff;
        background: #e9ecef;
    }
    .image-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <form action="{{ route('admin.master.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="card shadow" style="background-color: #fff;">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="bi bi-pencil-square me-2"></i> Tulis Artikel Baru
                        </h5>
                    </div>

                    <div class="card-body p-4 text-dark">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Judul Artikel</label>
                                    <input type="text" name="title" class="form-control" placeholder="Judul artikel..." required style="color: #000 !important;">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark">Isi Konten</label>
                                    <textarea id="summernote" name="content" rows="10" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light border-0 mb-3">
                                    <div class="card-body">
                                        <label class="form-label fw-bold text-dark">Gambar Sampul</label>
                                        
                                        <div class="image-preview-box mb-3" onclick="document.getElementById('imageInput').click()">
                                            <div id="placeholderText" class="text-center text-secondary">
                                                <i class="bi bi-cloud-arrow-up display-4"></i>
                                                <div class="mt-2 small">Klik upload gambar</div>
                                            </div>
                                            <img id="previewImage" src="#" alt="Preview">
                                        </div>
                                        
                                        <input type="file" name="image" id="imageInput" class="d-none" accept="image/*" onchange="previewFile(this)">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary fw-bold">Publikasikan</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- 2. JS LANGSUNG DI SINI (Agar pasti tereksekusi) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    // Gunakan window.onload untuk memastikan semua element siap
    window.onload = function() {
        if (typeof $ == 'undefined') {
            console.error("jQuery gagal dimuat!");
        } else {
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
        }
    };

    function previewFile(input) {
        var file = input.files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                // Pakai native JS selector biar aman
                document.getElementById("previewImage").src = reader.result;
                document.getElementById("previewImage").style.display = "block";
                document.getElementById("placeholderText").style.display = "none";
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection