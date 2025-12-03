<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        // Ambil semua setting dari tabel
        $data = DB::table('settings')->get();
        
        // Ubah format jadi Key-Value biar mudah dipakai di JS
        // Contoh output: {'nama_masjid': 'Al-Hidayah', 'running_text': 'Selamat Datang...'}
        $formatted = $data->pluck('value', 'key');

        return response()->json($formatted);
    }
}