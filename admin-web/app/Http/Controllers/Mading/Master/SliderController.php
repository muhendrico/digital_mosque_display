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
        $slidersResponse = Http::get(env('API_MADING_URL') . '/master/sliders');
        $sliders = $slidersResponse->successful() ? collect($slidersResponse->object()) : collect([]);
    
        $articlesResponse = Http::get(env('API_MADING_URL') . '/master/articles');
        $articles = $articlesResponse->successful() ? collect($articlesResponse->json()) : collect([]);
    
        return view('admin.mading.master.sliders', compact('sliders', 'articles'));
    }
    
    public function store(Request $request)
    {
        $http = Http::asMultipart(); 

        if ($request->hasFile('image')) {
            $http->attach(
                'image', 
                file_get_contents($request->file('image')), 
                $request->file('image')->getClientOriginalName()
            );
        }

        $response = $http->post(env('API_MADING_URL') . '/master/sliders', $request->except('image'));
    
        if ($response->successful()) {
            return redirect()->route('admin.master.sliders.index')
                ->with('success', 'Slider berhasil ditambahkan');
        } else {
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
            $msg = $response->json()['message'] ?? 'Gagal menghapus slider.';
            return back()->with('error', $msg);
        }
    }
}