{{-- GUNAKAN LAYOUT PUBLIC (BUKAN ADMIN) --}}
@extends('layouts.public')

@section('title', $article->title)

@section('css')
<style>
    /* KERTAS ARTIKEL (Blog Post Style) */
    .article-container {
        max-width: 850px; /* Lebar optimal untuk membaca */
        margin: 0 auto;   /* Tengah */
        background: #ffffff;
        padding: 60px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05); /* Shadow halus */
    }

    /* TYPOGRAPHY */
    .article-header {
        margin-bottom: 40px;
        text-align: center;
    }
    
    .article-title {
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 2.8rem; /* Judul Besar */
        color: #2c3e50;    /* Hitam Elegant */
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .article-meta {
        color: #6c757d;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 20px;
        background: #f8f9fa;
        padding: 10px 25px;
        border-radius: 50px;
    }

    /* GAMBAR UTAMA */
    .featured-image-wrapper {
        margin: -30px -60px 40px -60px; /* Melebar ke pinggir kertas */
        overflow: hidden;
        max-height: 500px;
    }
    .featured-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    /* BODY TEXT */
    .article-content {
        font-family: 'Merriweather', serif; /* Font serif enak untuk membaca panjang */
        font-size: 1.15rem;
        line-height: 1.9;
        color: #333;
    }
    
    /* Styling HTML Summernote */
    .article-content p { margin-bottom: 25px; }
    .article-content h2 { 
        font-family: 'Inter', sans-serif;
        font-weight: 700; 
        margin-top: 50px; 
        color: #17a2b8; /* Sub-judul Cyan */
    }
    .article-content blockquote {
        border-left: 4px solid #17a2b8;
        padding-left: 20px;
        margin: 30px 0;
        font-style: italic;
        color: #555;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }

    /* Responsive untuk HP */
    @media (max-width: 768px) {
        .article-container { padding: 30px; }
        .featured-image-wrapper { margin: -30px -30px 30px -30px; }
        .article-title { font-size: 2rem; }
    }
</style>
@endsection

@section('content')

    <div class="article-container">
        
        {{-- HEADER --}}
        <div class="article-header">
            <h1 class="article-title">{{ $article->title }}</h1>
            
            <div class="article-meta">
                <span><i class="bi bi-person-fill text-primary"></i> Admin</span>
                <span>•</span>
                <span><i class="bi bi-calendar-event text-primary"></i> {{ \Carbon\Carbon::parse($article->created_at)->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        {{-- GAMBAR UTAMA --}}
        @if($article->image)
            @php
                 $imgUrl = $article->image_url ?? env('API_URL').'/storage/'.$article->image;
            @endphp
            <div class="featured-image-wrapper">
                <img src="{{ $imgUrl }}" alt="{{ $article->title }}" class="featured-image">
            </div>
        @endif

        {{-- ISI ARTIKEL --}}
        <div class="article-content">
            {!! $article->content !!}
        </div>

    </div>

@endsection