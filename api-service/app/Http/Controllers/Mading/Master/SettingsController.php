<?php

namespace App\Http\Controllers\Mading\Master;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function index()
    {
        $data = DB::table('settings')->get();
        
        $formatted = $data->pluck('value', 'key');

        if (isset($formatted['qr_infaq'])) {
            $formatted['qr_infaq_url'] = 'http://localhost:8001/storage/' . $formatted['qr_infaq'];
        }

        return response()->json($formatted);
    }

    public function getPublicSettings()
    {
        $data = DB::table('settings')->get();
        
        $settings = $data->pluck('value', 'key');

        return response()->json($settings);
    }
}