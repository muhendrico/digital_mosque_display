<?php

namespace App\Http\Controllers\Mading\Master;

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
        $path_img = env('API_PATH_URL') . '/' . $settings['qr_infaq'];

        return view('admin.mading.master.settings', compact('settings', 'path_img'));
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
            'qr_infaq'    => 'nullable|image|max:2048',
        ]);

        // Ambil semua data input kecuali file & token
        $data = $request->except(['_token', '_method', 'qr_infaq']);
        
        // 1. PROSES UPLOAD FILE (Jika ada)
        if ($request->hasFile('qr_infaq')) {
            $path = $request->file('qr_infaq')->store('settings', 'public');
            // Masukkan path gambar ke dalam data yang akan disimpan ke DB
            $data['qr_infaq'] = $path;
        }

        // 2. SIMPAN KE DATABASE (Looping Key-Value)
        foreach ($data as $key => $value) {
            // Pastikan value tidak null (file upload bisa null jika tidak diupdate)
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}