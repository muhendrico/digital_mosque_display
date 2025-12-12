<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes (Lumen)
|--------------------------------------------------------------------------
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/auth/login', 'AuthController@login');

// GROUP UTAMA MADING
// URL Start: /v1/mading
// Namespace Root: App\Http\Controllers\Mading
$router->group(['prefix' => 'v1/mading', 'namespace' => 'Mading'], function () use ($router) {

    // 1. DASH (Tampilan TV)
    // URL: /v1/mading/dash/prayers
    // Controller Folder: Mading/Dash
    $router->group(['prefix' => 'dash', 'namespace' => 'Dash'], function () use ($router) {
        require __DIR__ . '/mading/dash.php';
    });

    // 2. MASTER (Data Admin)
    // URL: /v1/mading/master/sliders
    // Controller Folder: Mading/Master
    $router->group(['prefix' => 'master', 'namespace' => 'Master'], function () use ($router) {
        require __DIR__ . '/mading/master.php';
    });

    // 3. TRANS (Transaksi)
    // URL: /v1/mading/trans/finances
    // Controller Folder: Mading/Trans
    $router->group(['prefix' => 'trans', 'namespace' => 'Trans'], function () use ($router) {
        require __DIR__ . '/mading/trans.php';
    });

    // 4. REPORT (Laporan - Opsional)
    // $router->group(['prefix' => 'report', 'namespace' => 'Report'], function () use ($router) {
    //     require __DIR__ . '/mading/report.php';
    // });

});