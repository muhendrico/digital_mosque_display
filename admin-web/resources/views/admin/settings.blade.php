@extends('layouts.admin')

@section('title', 'Pengaturan Masjid')

@section('content')
<div class="container-fluid">
    
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-6">
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-building me-2"></i> Identitas Masjid</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-white-50">Nama Masjid</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white"><i class="bi bi-card-heading"></i></span>
                                <input type="text" name="nama_masjid" class="form-control" 
                                       value="{{ $settings['nama_masjid'] ?? '' }}" placeholder="Masukkan Nama Masjid">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white-50">Alamat Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-white"><i class="bi bi-geo-alt"></i></span>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. Contoh No. 123...">{{ $settings['alamat'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="bi bi-map me-2"></i> Titik Koordinat</h5>
                        <a href="https://www.google.com/maps" target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-search me-1"></i> Cari di Maps
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info bg-opacity-10 border-info text-info py-2 small">
                            <i class="bi bi-info-circle me-1"></i> Penting untuk akurasi jadwal sholat otomatis.
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50">Latitude</label>
                                <input type="text" name="latitude" class="form-control" 
                                       value="{{ $settings['latitude'] ?? '-6.175392' }}" placeholder="-6.xxxx">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50">Longitude</label>
                                <input type="text" name="longitude" class="form-control" 
                                       value="{{ $settings['longitude'] ?? '106.827153' }}" placeholder="106.xxxx">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-lg-6">
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-tv me-2"></i> Konfigurasi Layar</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50">Jeda Iqomah (Menit)</label>
                                <div class="input-group">
                                    <input type="number" name="iqomah_minutes" class="form-control text-center font-monospace fs-5" 
                                           value="{{ $settings['iqomah_minutes'] ?? '10' }}">
                                    <span class="input-group-text bg-dark border-secondary text-white">Menit</span>
                                </div>
                                <div class="form-text text-white-50 small">Hitung mundur setelah Adzan.</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50">Layar Mati/Standby (Menit)</label>
                                <div class="input-group">
                                    <input type="number" name="standby_minutes" class="form-control text-center font-monospace fs-5" 
                                           value="{{ $settings['standby_minutes'] ?? '10' }}">
                                    <span class="input-group-text bg-dark border-secondary text-white">Menit</span>
                                </div>
                                <div class="form-text text-white-50 small">Durasi layar gelap saat Sholat.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="bi bi-megaphone me-2"></i> Teks Berjalan (Running Text)</h5>
                    </div>
                    <div class="card-body">
                        
                        <div class="input-group mb-3">
                            <input type="text" id="newInfoInput" class="form-control" placeholder="Tulis info baru di sini (tekan Enter)...">
                            <button class="btn btn-info fw-bold" type="button" onclick="addRunningText()">
                                <i class="bi bi-plus-lg"></i> Tambah
                            </button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50 small">Daftar Info yang Ditampilkan:</label>
                            <ul class="list-group" id="infoListContainer">
                                </ul>
                            <div id="emptyMsg" class="text-white-50 small fst-italic mt-2" style="display: none;">Belum ada info. Silakan tambah di atas.</div>
                        </div>

                        <input type="hidden" name="running_text" id="realRunningText" value="{{ $settings['running_text'] ?? '' }}">

                        <div class="mt-4 text-end">
                             <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">
                                <i class="bi bi-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        renderList(); // Render data awal dari database saat load
    });

    // Ambil elemen
    const inputField = document.getElementById('newInfoInput');
    const hiddenField = document.getElementById('realRunningText');
    const listContainer = document.getElementById('infoListContainer');
    const emptyMsg = document.getElementById('emptyMsg');

    // Simbol pemisah (bisa diganti sesuai selera, misal " • " atau " | ")
    const SEPARATOR = " | "; 

    // 1. Fungsi Render List dari Hidden Input
    function renderList() {
        listContainer.innerHTML = '';
        const currentText = hiddenField.value;
        
        if(!currentText.trim()) {
            emptyMsg.style.display = 'block';
            return;
        } else {
            emptyMsg.style.display = 'none';
        }

        // Pecah string database menjadi array berdasarkan separator
        const items = currentText.split(SEPARATOR);

        items.forEach((text, index) => {
            if(text.trim() === '') return;

            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center bg-transparent text-white border-secondary mb-1 rounded';
            li.style.background = 'rgba(255,255,255,0.05)';
            
            li.innerHTML = `
                <span><i class="bi bi-dot me-2 text-info"></i> ${text}</span>
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeItem(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            `;
            listContainer.appendChild(li);
        });
    }

    // 2. Fungsi Tambah Item
    function addRunningText() {
        const newVal = inputField.value.trim();
        if(!newVal) return;

        let currentArr = hiddenField.value ? hiddenField.value.split(SEPARATOR) : [];
        currentArr.push(newVal);

        // Gabungkan kembali jadi string dan simpan ke hidden input
        hiddenField.value = currentArr.join(SEPARATOR);
        
        inputField.value = ''; // Reset input
        renderList(); // Refresh tampilan
    }

    // 3. Fungsi Hapus Item
    function removeItem(index) {
        let currentArr = hiddenField.value.split(SEPARATOR);
        currentArr.splice(index, 1); // Hapus array index tersebut

        hiddenField.value = currentArr.join(SEPARATOR);
        renderList();
    }

    // 4. Shortcut Enter Key di Input
    inputField.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Jangan submit form utama
            addRunningText();
        }
    });
</script>
<style>
    /* Membuat label lebih terbaca tapi tidak mencolok */
    .text-white-50 { color: rgba(255, 255, 255, 0.7) !important; font-size: 0.9rem; font-weight: 500; }
    
    /* Input field design: Background gelap transparan agar teks putih terbaca jelas */
    .form-control {
        background-color: rgba(0, 0, 0, 0.3) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: #fff !important;
        padding: 10px 15px;
        border-radius: 8px;
    }
    
    /* Fokus input lebih terang border-nya */
    .form-control:focus {
        background-color: rgba(0, 0, 0, 0.5) !important;
        border-color: #00BFFF !important;
        box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
    }

    /* Input Group Addon (Ikon di sebelah kiri input) */
    .input-group-text {
        background-color: rgba(0, 0, 0, 0.4) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
    }

    /* Card header dipisah garis halus */
    .card-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(0,0,0,0.2);
        padding: 15px 20px;
    }
</style>
@endsection