<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Prefix URL: /v1/mading/master/...
// Namespace: App\Http\Controllers\Mading\Master

// --- Settings ---
$router->get('settings', 'SettingsController@index');
$router->put('settings/{id}', 'SettingsController@update'); // Butuh ID biasanya
// Atau jika single row: $router->put('settings', 'SettingsController@update');

// --- Sliders (CRUD Manual karena Lumen tidak punya Resource) ---
$router->get('sliders', 'SliderController@index');
$router->post('sliders', 'SliderController@store');
$router->get('sliders/{id}', 'SliderController@show');
$router->put('sliders/{id}', 'SliderController@update');
$router->delete('sliders/{id}', 'SliderController@destroy');