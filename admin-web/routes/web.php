<?php

use Illuminate\Support\Facades\Route;

// Import Controller dengan Namespace BARU (Mading Structure)
// Pastikan Anda SUDAH memindahkan file controllernya sesuai langkah sebelumnya
use App\Http\Controllers\Mading\Dash\DashboardTvController;

/*
|--------------------------------------------------------------------------
| Web Routes (Admin Web)
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN UTAMA (TV DISPLAY) ---
// Akses: http://localhost:8000/
// Mengarahkan langsung ke tampilan TV (tanpa login)
Route::get('/', function () {
    // Arahkan ke View TV yang baru (sesuaikan path view anda jika sudah dipindah)
    // Jika masih path lama: return view('tv.index');
    return view('admin.mading.dash.tv_dashboard'); 
})->name('tv.root');


// --- 2. GROUP ADMIN (TANPA AUTH) ---
// Akses: http://localhost:8000/admin/...
Route::prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/', function () {
        return view('admin.mading.dash.admin_dashboard');
    })->name('dashboard'); // Nama route: admin.dashboard

    // --- LOAD ROUTE MODULAR ---
    // Kita panggil file-file route yang ada di folder routes/mading/
    
    // a. Master Data (Sliders, Settings) -> URL: /admin/master/...
    require __DIR__ . '/mading/master.php';

    // b. Transaksi (Finances) -> URL: /admin/trans/...
    require __DIR__ . '/mading/trans.php';

    // c. Report (Laporan) -> URL: /admin/report/...
    require __DIR__ . '/mading/report.php';

});