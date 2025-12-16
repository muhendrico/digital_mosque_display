<?php

namespace App\Http\Controllers\Mading\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
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

        $data = $request->except(['_token', '_method', 'qr_infaq']);
        
        if ($request->hasFile('qr_infaq')) {
            $path = $request->file('qr_infaq')->store('settings', 'public');
            $data['qr_infaq'] = $path;
        }

        foreach ($data as $key => $value) {
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