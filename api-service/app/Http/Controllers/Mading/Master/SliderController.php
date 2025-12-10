<?php

namespace App\Http\Controllers\Mading\Master;

use App\Http\Controllers\Controller;
use App\Models\Slider; // Pastikan Model Slider sudah ada di API Service juga
use Illuminate\Support\Facades\DB;

class SliderController extends Controller
{
    public function index()
    {
        // PENTING: Gunakan Model 'Slider::' bukan 'DB::table' 
        // agar kolom 'extra_data' otomatis berubah dari String JSON menjadi Array/Object
        $sliders = Slider::where('is_active', 1)
                    ->orderBy('order', 'asc')
                    ->get();
        
        $mapped = $sliders->map(function($item) {
            // LOGIKA URL GAMBAR
            if ($item->image_path === 'USE_DEFAULT_IMAGE') {
                // Jika default, arahkan ke file statis di folder public Admin (Port 8000)
                $item->image_url = 'http://localhost:8000/default-slide.jpg';
            } else {
                // Jika gambar biasa/video, arahkan ke storage
                $item->image_url = 'http://localhost:8000/storage/' . $item->image_path;
            }
            return $item;
        });

        return response()->json($mapped);
    }
}