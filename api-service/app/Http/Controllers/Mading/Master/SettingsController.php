<?php

namespace App\Http\Controllers\Mading\Master;

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

        // Jika ada qr_infaq, ubah jadi Full URL agar bisa dibuka TV
        if (isset($formatted['qr_infaq'])) {
            $formatted['qr_infaq_url'] = 'http://localhost:8000/storage/' . $formatted['qr_infaq'];
        }

        return response()->json($formatted);
    }
}