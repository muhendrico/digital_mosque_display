@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="stat-card blue">
            <div class="stat-value">3</div>
            <div class="stat-label">Slider Aktif</div>
            <i class="bi bi-images stat-icon"></i>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="stat-card green">
            <div class="stat-value">Rp 5jt</div>
            <div class="stat-label">Total Kas Masjid</div>
            <i class="bi bi-wallet2 stat-icon"></i>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="stat-card purple">
            <div class="stat-value">5</div>
            <div class="stat-label">Jadwal Sholat</div>
            <i class="bi bi-clock-history stat-icon"></i>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Selamat Datang di Admin Masjid</h3>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    Sistem ini menggunakan tema modern Glassmorphism. Anda bisa mengelola konten Slider dan Laporan Keuangan melalui menu di samping.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection