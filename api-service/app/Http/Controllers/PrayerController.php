<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrayerController extends Controller
{
    public function index()
    {
        // 1. Ambil Koordinat dari Database
        $settings = DB::table('settings')->pluck('value', 'key');
        $lat = $settings['latitude'] ?? '-6.175392'; // Default Jakarta
        $long = $settings['longitude'] ?? '106.827153';
        
        // 2. Tentukan Tanggal Hari Ini
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        // 3. Panggil API Aladhan (Disarankan menggunakan Guzzle, tapi file_get_contents cukup untuk simpel)
        // URL: http://api.aladhan.com/v1/timings/dd-mm-yyyy?lat=...&lng=...&method=20 (Kemenag RI = method 20, atau standar Muslim World League)
        // Untuk Indonesia biasanya Method 20 (Kemenag) belum tentu ada di semua library, 
        // kita pakai Method 1 (Muslim World League) atau Method 11 (Majlis Ugama Islam Singapura) yang mendekati, 
        // atau set custom params. Untuk simpel, kita pakai default dulu.
        
        $url = "http://api.aladhan.com/v1/timings/$day-$month-$year?latitude=$lat&longitude=$long&method=20";

        try {
            $json = file_get_contents($url);
            $data = json_decode($json, true);
            $timings = $data['data']['timings'];
            $hijri = $data['data']['date']['hijri'];

            // Filter hanya 5 waktu sholat + syuruq (terbit)
            return response()->json([
                'Subuh' => $timings['Fajr'],
                'Terbit' => $timings['Sunrise'], // Opsional
                'Dzuhur' => $timings['Dhuhr'],
                'Ashar' => $timings['Asr'],
                'Maghrib' => $timings['Maghrib'],
                'Isya' => $timings['Isha'],
                'date_readable' => $data['data']['date']['readable'], // Tanggal Masehi
                'date_hijri' => $hijri['day'] . ' ' . $hijri['month']['en'] . ' ' . $hijri['year'] . ' H'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil jadwal'], 500);
        }
    }
}