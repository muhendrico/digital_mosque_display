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

    public $successStatus = 200;

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
            'interval' => 'required|integer|min:1000', 
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
                'interval'   => $request->interval,
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

    public function show($id)
    {   
        try {
            $slider = Slider::find($id);

            $res = json_decode(json_encode($slider),true);
                
            if(count($res) > 0){ //mengecek apakah data kosong atau tidak
                $success['status'] = true;
                $success['data'] = $res;
                $success['message'] = "Success!";     
            }
            else{
                $success['message'] = "Data Tidak Ditemukan!";
                $success['data'] = [];
                $success['status'] = false;
            }
            return response()->json($success, $this->successStatus);
        } catch (\Throwable $e) {
            $success['status'] = false;
            $success['data'] = [];
            $success['message'] = "Error ".$e->getMessage();
            return response()->json($success, $this->successStatus);
        }
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);

        if (!$slider) {
            return response()->json([
                'status' => false,
                'message' => 'Data slider tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'    => 'required|string|max:100',
            'interval' => 'required|integer|min:1000',
            'image'    => 'nullable|file|mimes:jpeg,png,jpg,mp4|max:20480'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $slider->title = $request->title;
            $slider->interval = $request->interval;

            if ($request->hasFile('image')) {
                
                if ($slider->image_path && 
                    $slider->image_path !== 'USE_DEFAULT_IMAGE' && 
                    $slider->image_path !== 'default' &&
                    Storage::disk('public')->exists($slider->image_path)) {    
                    Storage::disk('public')->delete($slider->image_path);
                }

                $file = $request->file('image');
                $path = $file->store('sliders', 'public');
                $slider->image_path = $path;

                $mime = $file->getMimeType();
                
                if ($slider->type == 'image' || $slider->type == 'video') {
                    $slider->type = (strpos($mime, 'video') !== false) ? 'video' : 'image';
                }
            }

            if ($slider->type == 'infaq' && $request->has('quote')) {
                if (!empty($request->quote)) {
                    $slider->extra_data = ['quote' => $request->quote];
                }
            }
            
            if ($slider->type == 'article' && $request->has('article_id')) {
                $slider->article_id = $request->article_id;
            }

            $slider->save();

            return response()->json([
                'status' => true,
                'message' => 'Slider berhasil diperbarui',
                'data' => $slider
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal update slider: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan pada server saat update',
                'debug' => $e->getMessage()
            ], 500);
        }
    }
}