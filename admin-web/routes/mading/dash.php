<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mading\Dash\DashboardTvController;

Route::prefix('dash')->name('dash.')->group(function () {
    Route::get('/', function () { return view('admin.dash.tv_dashboard'); })->name('index'); 
    Route::get('/tv-preview', [DashboardTvController::class, 'index'])->name('tv.preview');
});
