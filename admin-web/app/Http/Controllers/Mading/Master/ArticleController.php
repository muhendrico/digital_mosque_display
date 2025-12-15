<?php

namespace App\Http\Controllers\Mading\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ArticleController extends Controller
{
    private $apiUrl;

    public function __construct() {
        $this->apiUrl = env('API_MADING_URL');
    }

    public function index()
    {
        $response = Http::get($this->apiUrl . '/master/articles');
        $articles = collect($response->object());

        return view('admin.mading.master.articles', compact('articles'));
    }

    public function create()
    {
        return view('admin.mading.master.create_articles');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|min:5|max:255',
            'content' => 'required|min:40',
            'image'   => 'required|image|mimes:jpeg,png,jpg|max:20480',
        ], [
            'title.required'   => 'Judul artikel wajib diisi.',
            'title.min'        => 'Judul minimal 5 karakter.',
            'content.required' => 'Isi konten artikel tidak boleh kosong.',
            'content.min'      => 'Konten artikel terlalu pendek (minimal 40 karakter).',
            'image.required'   => 'Gambar artikel wajib diupload.',
            'image.image'      => 'File harus berupa gambar.',
            'image.max'        => 'Ukuran gambar maksimal 5MB.',
        ]);

        $url = env('API_MADING_URL') . '/master/articles';

        $data = [
            'title' => $request->title,
            'content' => $request->content,
        ];

        $http = Http::asMultipart();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            $http->attach(
                'image', 
                file_get_contents($file), 
                $file->getClientOriginalName()
            );
        }

        $response = $http->post($url, $data);

        if ($response->successful()) {
            return redirect()->route('admin.master.articles.index')->with('success', 'Berhasil!');
        } else {
            return back()->withErrors($response->json()['errors'] ?? 'Gagal menyimpan.');
        }
    }

    public function destroy($id)
    {
        $url = env('API_MADING_URL') . '/master/articles/' . $id;

        $response = Http::delete($url);
        
        if ($response->successful()) {
            return back()->with('success', 'Artikel berhasil dihapus!');
        } else {
            $msg = $response->json()['message'] ?? 'Gagal menghapus artikel.';
            return back()->with('error', $msg);
        }
    }
    
    public function show($slug)
    {
        $url = env('API_MADING_URL') . '/master/articles/slug/' . $slug;
        $response = Http::get($url);

        if ($response->failed()) {
            return redirect()->route('admin.master.articles.index')->with('error', 'Artikel tidak ditemukan.');
        }

        $article = $response->object(); 

        return view('admin.mading.master.show_articles', compact('article'));
    }

    public function edit($id)
    {
        $response = Http::get(env('API_MADING_URL') . '/master/articles/' . $id);
        
        if($response->failed()) {
            return back()->with('error', 'Artikel tidak ditemukan');
        }

        $article = $response->object();
        return view('admin.mading.master.edit_articles', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'   => 'required|min:5|max:255',
            'content' => 'required|min:20',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg|max:20480',
        ], [
            'title.required'   => 'Judul artikel wajib diisi.',
            'title.min'        => 'Judul minimal 5 karakter.',
            'content.required' => 'Isi konten artikel tidak boleh kosong.',
            'image.max'        => 'Ukuran gambar maksimal 5MB.',
        ]);

        $http = Http::asMultipart();

        if ($request->hasFile('image')) {
            $http->attach(
                'image', 
                file_get_contents($request->file('image')), 
                $request->file('image')->getClientOriginalName()
            );
        }

        $response = $http->post(env('API_MADING_URL') . '/master/articles/' . $id . '/update', [
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($response->successful()) {
            return redirect()->route('admin.master.articles.index')->with('success', 'Artikel berhasil diupdate!');
        }

        return back()->with('error', 'Gagal update: ' . ($response->json()['message'] ?? 'Error API'));
    }
}