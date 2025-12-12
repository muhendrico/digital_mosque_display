<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Prefix URL: /v1/mading/master/...
// Namespace: App\Http\Controllers\Mading\Master

// --- Settings ---
$router->get('settings', 'SettingsController@index');
$router->put('settings/{id}', 'SettingsController@update');
// Route Public untuk Settings
$router->get('/public/settings', 'SettingsController@getPublicSettings');

// --- Sliders  ---
$router->get('sliders', 'SliderController@index');
$router->post('sliders', 'SliderController@store');
$router->get('sliders/{id}', 'SliderController@show');
$router->put('sliders/{id}', 'SliderController@update');
$router->delete('sliders/{id}', 'SliderController@destroy');

// Route Artikel (CRUD)
$router->get('articles', 'ArticleController@index');
$router->post('articles', 'ArticleController@store');
$router->delete('articles/{id}', 'ArticleController@destroy');

// Route Artikel Detail (Untuk Public/Web Frontend nanti)
$router->get('articles/{slug}', 'ArticleController@show');
