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
            'title' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // Proses Upload
        if ($request->hasFile('image')) {
            // Simpan ke folder 'public/sliders'
            $path = $request->file('image')->store('sliders', 'public');

            Slider::create([
                'title' => $request->title,
                'image_path' => $path, // Yang disimpan hanya path-nya, misal: sliders/gambar1.jpg
                'is_active' => true
            ]);
        }

        return redirect()->back()->with('success', 'Gambar berhasil diupload!');
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