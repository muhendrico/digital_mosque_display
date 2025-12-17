<?php

namespace App\Http\Controllers\Mading\Master;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

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

    public function show($id){
        try{        
            $client = new Client();
            $response = $client->request('GET',  env('API_MADING_URL') . '/master/sliders/' . $id);

            if ($response->getStatusCode() == 200) { // 200 OK
                $response_data = $response->getBody()->getContents();
                $data = json_decode($response_data,true);
            }
            return response()->json($data, 200);

        } catch (BadResponseException $ex) {
            $response = $ex->getResponse();
            $res = json_decode($response->getBody(),true);
            return response()->json(['message' => $res["message"], 'status'=>false], 200);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $client = new Client();
            $multipart = [];

            $multipart[] = [
                'name'     => '_method',
                'contents' => 'PUT'
            ];

            foreach ($request->except(['image', '_token', '_method']) as $key => $value) {
                $multipart[] = [
                    'name'     => $key,
                    'contents' => $value
                ];
            }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $multipart[] = [
                    'name'     => 'image',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ];
            }

            $response = $client->request('POST', env('API_MADING_URL') . '/master/sliders/' . $id, [
                'multipart' => $multipart
            ]);

            if ($response->getStatusCode() == 200) { 
                $response_data = $response->getBody()->getContents();
                $data = json_decode($response_data, true);
                
                return response()->json($data, 200);
            }

        } catch (BadResponseException $ex) {
            $response = $ex->getResponse();
            $res = json_decode($response->getBody(), true);
            
            return response()->json([
                'message' => $res["message"] ?? 'Terjadi kesalahan pada server', 
                'status'  => false,
                'errors'  => $res["errors"] ?? null
            ], $response->getStatusCode());
        }
    }
}