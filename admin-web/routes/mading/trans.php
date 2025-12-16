<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mading\Trans\FinanceController;

Route::prefix('trans')->name('trans.')->group(function () {
    Route::resource('finances', FinanceController::class);
});