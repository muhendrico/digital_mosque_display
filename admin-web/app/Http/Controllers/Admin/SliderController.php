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
            // Update validasi: Boleh Gambar (jpeg,png,jpg) ATAU Video (mp4)
            // Max ukuran kita naikkan misal jadi 20MB (20480 KB) untuk video pendek
            'image' => 'required|file|mimes:jpeg,png,jpg,mp4|max:20480', 
        ]);
    
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('sliders', 'public');
    
            // DETEKSI TIPE FILE OTOMATIS
            // Ambil MIME type (contoh: 'image/jpeg' atau 'video/mp4')
            $mime = $file->getMimeType();
            // Ambil kata depannya saja ('image' atau 'video')
            $type = explode('/', $mime)[0]; 
    
            Slider::create([
                'title' => $request->title,
                'image_path' => $path,
                'type' => $type, // Simpan tipenya ('image' atau 'video')
                'is_active' => true
            ]);
        }
    
        return redirect()->back()->with('success', 'Media berhasil diupload!');
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