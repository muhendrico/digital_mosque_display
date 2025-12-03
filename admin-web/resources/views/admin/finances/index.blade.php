@extends('layouts.admin')

@section('title', 'Laporan Kas Masjid')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-success bg-gradient text-white border-0 shadow">
                <div class="card-body d-flex justify-content-between align-items-center p-4">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Total Saldo Saat Ini</h6>
                        <h1 class="display-4 fw-bold mb-0">Rp {{ number_format($saldo, 0, ',', '.') }}</h1>
                    </div>
                    <div class="fs-1 opacity-25">
                        <i class="bi bi-wallet-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary bg-opacity-10">
                    <h5 class="card-title mb-0 text-primary-emphasis"><i class="bi bi-pencil-square me-2"></i> Catat Transaksi</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.finances.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label text-white-50">Tanggal</label>
                            <input type="date" name="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50">Jenis Transaksi</label>
                            <select name="type" class="form-select" required>
                                <option value="pemasukan" class="text-dark">🟢 Pemasukan (Infaq/Donasi)</option>
                                <option value="pengeluaran" class="text-dark">🔴 Pengeluaran (Belanja)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50">Nominal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="amount" class="form-control font-monospace" placeholder="0" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-white-50">Keterangan</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Contoh: Kotak Jumat..." required></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="bi bi-save me-2"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i> Riwayat Transaksi</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white sticky-top" style="z-index: 1;">
                                <tr>
                                    <th class="ps-3">Tanggal</th>
                                    <th>Keterangan</th>
                                    <th class="text-end">Masuk</th>
                                    <th class="text-end">Keluar</th>
                                    <th class="text-center pe-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($finances as $data)
                                <tr>
                                    <td class="ps-3 text-nowrap">{{ date('d/m/Y', strtotime($data->transaction_date)) }}</td>
                                    <td>{{ $data->description }}</td>
                                    <td class="text-end text-success fw-bold">
                                        {{ $data->type == 'pemasukan' ? number_format($data->amount,0,',','.') : '-' }}
                                    </td>
                                    <td class="text-end text-danger fw-bold">
                                        {{ $data->type == 'pengeluaran' ? number_format($data->amount,0,',','.') : '-' }}
                                    </td>
                                    <td class="text-center pe-3">
                                        <form action="{{ route('admin.finances.delete', $data->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger border-0">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-white-50">
                                        <i class="bi bi-clipboard-x display-6 d-block mb-3 opacity-50"></i>
                                        Belum ada data transaksi.
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
    /* Style Tabel */
    .table { color: #fff; margin-bottom: 0; }
    .table thead th { background-color: #2236ed; color: #fff; border-bottom: 2px solid rgba(255,255,255,0.1); }
    .table td { border-bottom: 1px solid rgba(255,255,255,0.1); font-size: 0.95rem; }
    .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.1); }
    
    /* Scrollbar Tipis untuk tabel */
    .table-responsive::-webkit-scrollbar { width: 6px; }
    .table-responsive::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
    .table-responsive::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }
</style>
@endsection