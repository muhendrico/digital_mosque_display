<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache; // <--- Penting untuk performa

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // View Composer: Jalankan kode ini HANYA saat 'layouts.public' dimuat
        View::composer('layouts.public', function ($view) {
            
            // GUNAKAN CACHE (60 Menit)
            // Agar tidak menembak API terus menerus setiap reload halaman
            $settings = Cache::remember('public_settings', 60, function () {
                try {
                    // Tembak API Lumen
                    $response = Http::get(env('API_MADING_URL') . '/master/public/settings');
                    
                    if ($response->successful()) {
                        return $response->json();
                    }
                } catch (\Exception $e) {
                    // Jika API mati, return array kosong agar web tidak error
                    return [];
                }
                return [];
            });

            // Kirim variabel $settings ke View
            $view->with('settings', $settings);
        });
    }
}