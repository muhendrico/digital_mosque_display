<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order', 'asc')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:100',
            'slider_type' => 'required|in:media,infaq', 
        ]);
    
        $type = 'image';
        $path = null;
        $extraData = null;

        // A. SLIDE MEDIA BIASA
        if ($request->slider_type == 'media') {
            $request->validate(['image' => 'required|file|mimes:jpeg,png,jpg,mp4|max:20480']);
            $file = $request->file('image');
            $path = $file->store('sliders', 'public');
            $mime = $file->getMimeType();
            $type = explode('/', $mime)[0]; 
        }
        // B. SLIDE INFAQ / MOTIVASI (REVISI)
        else if ($request->slider_type == 'infaq') {
            $request->validate([
                'quote' => 'required|string|max:500',
                'bg_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5048', 
            ]);
    
            $type = 'infaq';
            
            // Cek Upload
            if($request->hasFile('bg_image')){
                // Simpan file user
                $path = $request->file('bg_image')->store('sliders', 'public');
            } else {
                // Gunakan KODE ini untuk memanggil gambar default yang kita taruh di folder public tadi
                $path = 'USE_DEFAULT_IMAGE'; 
            }
    
            $extraData = ['quote' => $request->quote];
        }

        Slider::create([
            'title' => $request->title,
            'image_path' => $path, // Bisa null jika infaq tidak pakai gambar
            'type' => $type,
            'extra_data' => $extraData,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Slide berhasil ditambahkan!');
        }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        
        // Hapus file fisik dari storage
        if(Storage::disk('public')->exists($slider->image_path)){
            Storage::disk('public')->delete($slider->image_path);
        }
        
        $slider->delete();
        return redirect()->back()->with('success', 'Gambar dihapus!');
    }
}