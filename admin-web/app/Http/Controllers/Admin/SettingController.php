<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil data settings dan ubah formatnya biar mudah dipanggil di View
        // Dari format baris database menjadi array: ['nama_masjid' => 'Nilai', ...]
        $settings = Setting::pluck('value', 'key');
        
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_masjid' => 'required',
            'alamat' => 'required',
            'running_text' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'iqomah_minutes' => 'required|numeric', 
            'standby_minutes' => 'required|numeric',
        ]);

        $data = $request->only([
            'nama_masjid', 
            'alamat', 
            'running_text', 
            'latitude', 
            'longitude',
            'iqomah_minutes',
            'standby_minutes'
        ]);
        
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}