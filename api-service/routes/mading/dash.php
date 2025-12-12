<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Prefix URL: /v1/mading/dash/...
// Namespace sudah di-set di web.php menjadi: App\Http\Controllers\Mading\Dash

$router->get('prayers', 'PrayerController@index');
// $router->get('tv-content', 'TvDataController@index'); // Contoh jika ada