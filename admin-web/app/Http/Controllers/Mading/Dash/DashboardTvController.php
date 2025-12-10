<?php

namespace App\Http\Controllers\Mading\Dash;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardTvController extends Controller
{
    /**
     * Menampilkan halaman utama TV (Digital Signage)
     */
    public function index()
    {
        return view('admin.mading.dash.tv_dashboard');
    }
}