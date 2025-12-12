<?php

namespace App\Http\Controllers\Mading\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::with('article')
                    ->where('is_active', 1)
                    ->orderBy('order', 'asc')
                    ->get();
        
        $mapped = $sliders->map(function($item) {
            if ($item->image_path === 'USE_DEFAULT_IMAGE') {
                $item->image_url = 'http://localhost:8000/default-slide.jpg';
            } else {
                $item->image_url = 'http://localhost:8000/storage/' . $item->image_path;
            }
            return $item;
        });

        return response()->json($mapped);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'nullable|string|max:100',
            'slider_type' => 'required|in:media,infaq,article', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $type = 'image';
            $path = null;
            $extraData = null;
            $articleId = null;

            // A. SLIDE MEDIA BIASA
            if ($request->slider_type == 'media') {
                $valMedia = Validator::make($request->all(), [
                    'image' => 'required|file|mimes:jpeg,png,jpg,mp4|max:20480'
                ]);
                
                if ($valMedia->fails()) {
                    return response()->json(['errors' => $valMedia->errors()], 422);
                }

                $file = $request->file('image');
                $path = $file->store('sliders', 'public');
                $mime = $file->getMimeType();
                $type = (strpos($mime, 'video') !== false) ? 'video' : 'image';
            }
            
            // B. SLIDE INFAQ / MOTIVASI
            else if ($request->slider_type == 'infaq') {
                $valInfaq = Validator::make($request->all(), [
                    'quote' => 'required|string|max:500',
                    'bg_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5048', 
                ]);

                if ($valInfaq->fails()) {
                    return response()->json(['errors' => $valInfaq->errors()], 422);
                }
        
                $type = 'infaq';
                
                if($request->hasFile('bg_image')){
                    $path = $request->file('bg_image')->store('sliders', 'public');
                } else {
                    $path = 'USE_DEFAULT_IMAGE'; 
                }
        
                $extraData = ['quote' => $request->quote];
            }
            
            // C. SLIDE ARTIKEL
            else if ($request->slider_type == 'article') {
                $valArticle = Validator::make($request->all(), [
                    'article_id' => 'required|exists:articles,id',
                ]);

                if ($valArticle->fails()) {
                    return response()->json(['errors' => $valArticle->errors()], 422);
                }
        
                $type = 'article';
                $path = 'USE_DEFAULT_IMAGE';
                $articleId = $request->article_id;
            }

            // Simpan ke Database
            $slider = Slider::create([
                'title'      => $request->title,
                'image_path' => $path,
                'type'       => $type,
                'extra_data' => $extraData,
                'article_id' => $articleId,
                'is_active'  => true
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Slider berhasil ditambahkan',
                'data' => $slider
            ], 201);

        } catch (\Exception $e) {
            Log::error('Gagal simpan slider: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan pada server',
                'debug' => $e->getMessage() // Hapus baris ini saat production
            ], 500);
        }
    }

    public function destroy($id)
    {
        $slider = Slider::find($id);

        if (!$slider) {
            return response()->json(['message' => 'Slider tidak ditemukan'], 404);
        }

        if ($slider->image && Storage::disk('public')->exists($slider->image_path)) {
            try {
                Storage::disk('public')->delete($slider->image_path);
            } catch (\Exception $e) {
                Log::error("Gagal hapus file gambar: " . $e->getMessage());
            }
        }

        $slider->delete();

        return response()->json(['message' => 'Slider berhasil dihapus']);
    }
}