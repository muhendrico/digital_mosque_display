<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = DB::table('sliders')
                    ->where('is_active', 1)
                    ->orderBy('order', 'asc')
                    ->get();
        
        // Kita harus melengkapi path gambar agar menjadi URL penuh
        // Karena gambarnya ada di service Admin (port 8000), kita hardcode base url-nya
        $mapped = $sliders->map(function($item) {
            $item->image_url = 'http://localhost:8000/storage/' . $item->image_path;
            return $item;
        });

        return response()->json($mapped);
    }
}