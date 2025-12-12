<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mading\Master\SliderController;
use App\Http\Controllers\Mading\Master\SettingController;
use App\Http\Controllers\Mading\Master\ArticleController;

Route::prefix('master')->name('master.')->group(function () {
    
    // Sliders
    Route::resource('sliders', SliderController::class);
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Articles
    Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
    Route::resource('articles', ArticleController::class);
});