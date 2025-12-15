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
$router->post('/articles/{id}/update', 'ArticleController@update');

// // Route Artikel Detail (Untuk Public/Web Frontend)
// $router->get('articles/{slug}', 'ArticleController@show');

// Endpoint untuk ADMIN (Cari berdasarkan ID)
$router->get('/articles/{id:[0-9]+}', 'ArticleController@show');

// Endpoint untuk PUBLIC (Cari berdasarkan SLUG)
$router->get('/articles/slug/{slug}', 'ArticleController@getBySlug');