<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mading\Dash\DashboardTvController;

/*
|--------------------------------------------------------------------------
| Web Routes (Admin Web)
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN UTAMA (TV DISPLAY) ---
// Akses: http://localhost:8000/
Route::get('/', function () {
    return view('admin.mading.dash.tv_dashboard'); 
})->name('tv.root');

// --- 2. AUTHENTICATION ROUTES (Login/Logout/Register) ---
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// --- 3. GROUP ADMIN ---
// Akses: http://localhost:8000/admin/...
// Gunakan ->name('admin.') dengan titik di akhir
Route::prefix('admin')
    ->name('admin.') // Prefix nama route
    ->middleware(['admin.auth'])
    ->group(function () {

        Route::get('/', function () {
            return view('admin.mading.dash.admin_dashboard');
        })->name('dashboard'); 
        // Hasil: admin.dashboard

    // --- LOAD ROUTE MODULAR ---
    // Kita panggil file-file route yang ada di folder routes/mading/
    
    // a. Master Data (Sliders, Settings) -> URL: /admin/master/...
    require __DIR__ . '/mading/master.php';

    // b. Transaksi (Finances) -> URL: /admin/trans/...
    require __DIR__ . '/mading/trans.php';

    // c. Report (Laporan) -> URL: /admin/report/...
    require __DIR__ . '/mading/report.php';

});

// --- 4. Route Public ---
Route::get('/{slug}', [App\Http\Controllers\Mading\Master\ArticleController::class, 'show'])
    ->name('public.article.show');