<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mading\Master\SliderController;
use App\Http\Controllers\Mading\Master\SettingController;

// Group ini otomatis tersambung dengan prefix 'admin' dari web.php
// Jadi URL-nya menjadi: /admin/master/sliders

Route::prefix('master')->name('master.')->group(function () {
    
    // Menggantikan Route::get, post, delete sekaligus
    Route::resource('sliders', SliderController::class);
    
    // Khusus Setting (biasanya cuma Edit/Update, bukan full resource)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Nanti Artikel ditaruh sini
    // Route::resource('articles', ArticleController::class);
});