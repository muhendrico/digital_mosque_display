<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 
        'image_path', 
        'type', 
        'extra_data', 
        'order', 
        'is_active',
        'article_id',
        'interval',
    ];

    protected $casts = [
        'extra_data' => 'array', // Ini akan otomatis mengubah Array <-> JSON
        'is_active' => 'boolean',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // Field tambahan yang akan dikirim ke JSON
    protected $appends = ['image_url'];

    // --- ACCESSOR: PEMBUAT URL GAMBAR ---
    public function getImageUrlAttribute()
    {
        // 1. Jika tipe Infaq (tidak ada gambar, cuma quote), return null/default
        if ($this->type == 'infaq' && empty($this->image_path)) {
            return null; // Atau URL gambar background default
        }

        if ($this->image_path === 'USE_DEFAULT_IMAGE' || $this->image_path === 'default') {
            $baseUrl = rtrim(env('APP_URL'), '/');
            
            return $baseUrl . '/storage/' . 'default-slide.jpg'; 
        }

        // 2. Jika ada path gambar
        if ($this->image_path) {
            $baseUrl = rtrim(env('APP_URL'), '/');
            
            return $baseUrl . '/storage/' . $this->image_path;
        }

        return null;
    }
}