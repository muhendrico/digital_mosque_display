<?php

/** @var \Laravel\Lumen\Routing\Router $router */

// Prefix URL: /v1/mading/trans/...
// Namespace: App\Http\Controllers\Mading\Trans

// --- Finances (CRUD Manual) ---
$router->get('finances', 'FinanceController@index');
$router->post('finances', 'FinanceController@store');
$router->put('finances/{id}', 'FinanceController@update');
$router->delete('finances/{id}', 'FinanceController@destroy');