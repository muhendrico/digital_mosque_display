<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingController;

// Halaman TV (Akses root langsung ke TV biar mudah)
Route::get('/', function () {
    return view('tv.index');
});

// Group route untuk admin (biar rapi)
Route::prefix('admin')->group(function () {
    // Dashboard (yang lama)
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Route Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
    
    // Route Sliders
    Route::get('/sliders', [App\Http\Controllers\Admin\SliderController::class, 'index'])->name('admin.sliders');
    Route::post('/sliders', [App\Http\Controllers\Admin\SliderController::class, 'store'])->name('admin.sliders.store');
    Route::delete('/sliders/{id}', [App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('admin.sliders.delete');    

    // Route Finances
    Route::get('/finances', [App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('admin.finances');
    Route::post('/finances', [App\Http\Controllers\Admin\FinanceController::class, 'store'])->name('admin.finances.store');
    Route::delete('/finances/{id}', [App\Http\Controllers\Admin\FinanceController::class, 'destroy'])->name('admin.finances.delete');
});