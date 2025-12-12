<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'title', 
        'content', 
        'image', 
        'slug',   
        'summary',
    ];

    // Tambahkan properti virtual 'image_url' agar muncul di JSON
    protected $appends = ['image_url'];

    // Logic pembuatan URL lengkap
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $baseUrl = rtrim(env('APP_URL'), '/'); 
            
            return $baseUrl . '/storage/' . $this->image;
        }

        return null;
    }
}