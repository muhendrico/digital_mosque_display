<?php

namespace App\Http\Controllers\Mading\Master;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Slider (Existing)
        $slidersResponse = Http::get(env('API_MADING_URL') . '/master/sliders');
        $sliders = $slidersResponse->successful() ? collect($slidersResponse->object()) : collect([]);
    
        // 2. TAMBAHAN: Ambil Data Artikel untuk Dropdown
        $articlesResponse = Http::get(env('API_MADING_URL') . '/master/articles');
        $articles = $articlesResponse->successful() ? collect($articlesResponse->json()) : collect([]);
    
        // 3. Kirim kedua variabel ke View
        return view('admin.mading.master.sliders', compact('sliders', 'articles'));
    }
    
    public function store(Request $request)
    {
        // 1. Siapkan Request
        $http = Http::asMultipart(); 

        if ($request->hasFile('image')) {
            $http->attach(
                'image', 
                file_get_contents($request->file('image')), 
                $request->file('image')->getClientOriginalName()
            );
        }

        // 2. Kirim ke API
        $response = $http->post(env('API_MADING_URL') . '/master/sliders', $request->except('image'));
    
        // 3. LOGIC PENGECEKAN (WAJIB)
        if ($response->successful()) {
            // SKENARIO SUKSES (Status 200/201)
            return redirect()->route('admin.master.sliders.index')
                ->with('success', 'Slider berhasil ditambahkan');
        } else {
            // SKENARIO GAGAL (Status 400, 422, 500)
            $errors = $response->json()['errors'] ?? null;
            $message = $response->json()['message'] ?? 'Gagal menyimpan data ke server.';

            return back()
                ->withInput()          
                ->withErrors($errors)   
                ->with('error', $message); 
        }
    }

    public function destroy($id)
    {
        $url = env('API_MADING_URL') . '/master/sliders/' . $id;
        $response = Http::delete($url);

        if ($response->successful()) {
            return back()->with('success', 'Slider berhasil dihapus!');
        } else {
            // Ambil pesan error dari backend jika ada
            $msg = $response->json()['message'] ?? 'Gagal menghapus slider.';
            return back()->with('error', $msg);
        }
    }
}