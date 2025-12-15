<?php

namespace App\Http\Controllers\Mading\Master;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        return response()->json(Article::latest()->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5048',
        ]);

        $slug = Str::slug($request->title) . '-' . time();
        $summary = Str::limit(strip_tags($request->content), 150);
        $path = null;
        $path = $request->file('image')->store('articles', 'public');
                
        $article = Article::create([
            'title'   => $request->title,
            'content' => $request->content,
            'image'   => $path,
            'slug'    => $slug,
            'summary' => $summary,
        ]);

        return response()->json($article);
    }

    public function update(Request $request, $id)
    {
        // 1. Cari Artikel
        $article = Article::find($id);
        if (!$article) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        // 2. Validasi (Judul wajib, Gambar Opsional karena mungkin tidak diganti)
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Nullable
        ]);

        try {
            // 3. Logic Update Gambar
            $currentImage = $article->image;

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada (dan bukan default)
                if ($currentImage && Storage::disk('public')->exists($currentImage)) {
                    Storage::disk('public')->delete($currentImage);
                }
                // Upload gambar baru
                $currentImage = $request->file('image')->store('articles', 'public');
            }

            // 4. Update Database
            $article->update([
                'title' => $request->title,
                // Update slug jika judul berubah (opsional, tapi disarankan)
                'slug' => Str::slug($request->title) . '-' . time(),
                'content' => $request->content,
                'image' => $currentImage
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Artikel berhasil diperbarui',
                'data' => $article
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal update: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $article = Article::find($id);
        if (!$article) return response()->json(['message' => 'Not Found'], 404);
        
        // Inject URL gambar
        $article->image_url = $article->image ? url('storage/' . $article->image) : null;
        
        return response()->json($article);
    }
    
    // Method BARU untuk Public (Cari by Slug)
    public function getBySlug($slug)
    {
        $article = Article::where('slug', $slug)->first();
        
        if (!$article) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }
    
        $article->image_url = $article->image ? url('storage/' . $article->image) : null;
    
        return response()->json($article);
    }

    public function destroy($id)
    {
        // 1. Cari Artikel
        $article = Article::find($id);

        if (!$article) {
            return response()->json(['message' => 'Artikel tidak ditemukan'], 404);
        }

        // 2. Hapus File Gambar (Jika ada)
        // Cek apakah kolom image ada isinya DAN file-nya benar-benar ada di storage
        if ($article->image && Storage::disk('public')->exists($article->image)) {
            try {
                Storage::disk('public')->delete($article->image);
            } catch (\Exception $e) {
                // Jika error hapus file (misal karena library flysystem bermasalah), 
                // kita log saja dan lanjut hapus data DB agar tidak macet.
                Log::error("Gagal hapus file gambar: " . $e->getMessage());
            }
        }

        // 3. Hapus Data di Database
        $article->delete();

        return response()->json(['message' => 'Artikel berhasil dihapus']);
    }
}