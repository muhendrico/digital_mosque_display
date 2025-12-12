<?php

namespace App\Http\Controllers\Mading\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ArticleController extends Controller
{
    // URL API (Ambil dari .env lebih baik)
    private $apiUrl;

    public function __construct() {
        $this->apiUrl = env('API_MADING_URL');
    }

    public function index()
    {
        // Ambil data dari API Backend
        $response = Http::get($this->apiUrl . '/master/articles');
        $articles = collect($response->object());

        // View masuk ke folder admin/mading/master
        return view('admin.mading.master.articles', compact('articles'));
    }

    public function create()
    {
        return view('admin.mading.master.create_articles');
    }
    // Di Controller FRONTEND (Laravel), function store:

    public function store(Request $request)
    {
        // 1. Siapkan URL API Backend
        $url = env('API_MADING_URL') . '/master/articles';

        // 2. Siapkan Data Text
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            // Jangan masukkan 'image' disini dulu
        ];

        // 3. LOGIC PENGIRIMAN (GUNAKAN HTTP CLIENT)
        $http = Http::asMultipart(); // <--- PENTING: Mode Multipart

        // Cek apakah user upload gambar di Frontend?
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // LAMPIRKAN FILE (ATTACH)
            // Parameter: nama_field, isi_file, nama_file_asli
            $http->attach(
                'image', 
                file_get_contents($file), 
                $file->getClientOriginalName()
            );
        }

        // 4. Kirim Request POST ke Backend
        $response = $http->post($url, $data);

        // 5. Cek Balasan Backend
        if ($response->successful()) {
            return redirect()->route('admin.master.articles.index')->with('success', 'Berhasil!');
        } else {
            // Tampilkan error dari Backend (JSON)
            return back()->withErrors($response->json()['errors'] ?? 'Gagal menyimpan.');
        }
    }

    public function destroy($id)
    {
        // 1. Siapkan URL API (Gabungkan URL + ID)
        // Hasil: http://localhost:8000/api/article/5
        $url = env('API_MADING_URL') . '/master/articles/' . $id;

        // 2. Kirim Request DELETE
        $response = Http::delete($url);
        // 3. Cek Hasil
        if ($response->successful()) {
            return back()->with('success', 'Artikel berhasil dihapus!');
        } else {
            // Ambil pesan error dari backend jika ada
            $msg = $response->json()['message'] ?? 'Gagal menghapus artikel.';
            return back()->with('error', $msg);
        }
    }
    
    public function show($slug)
    {
        // 1. Ambil data dari API Lumen
        $url = env('API_MADING_URL') . '/master/articles/' . $slug;
        $response = Http::get($url);

        if ($response->failed()) {
            return redirect()->route('admin.master.articles.index')->with('error', 'Artikel tidak ditemukan.');
        }

        $article = $response->object(); // Ubah JSON jadi Object

        // 2. Tampilkan View
        return view('admin.mading.master.show_articles', compact('article'));
    }
    // ... method destroy dll ...
}