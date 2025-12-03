<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultDataSeeder extends Seeder
{
    public function run()
    {
        // Data Settings
        $settings = [
            ['key' => 'nama_masjid', 'value' => 'Masjid Raya Al-Hidayah'],
            ['key' => 'alamat', 'value' => 'Jl. Merdeka No. 45, Bandung'],
            ['key' => 'running_text', 'value' => 'Selamat Datang di Masjid Raya Al-Hidayah. Mohon luruskan dan rapatkan shaf. Matikan HP Anda.'],
        ];
        DB::table('settings')->insert($settings);

        // Data Keuangan Contoh
        DB::table('finances')->insert([
            [
                'transaction_date' => now(),
                'type' => 'pemasukan',
                'amount' => 5000000,
                'description' => 'Infaq Jumat Lalu',
            ],
            [
                'transaction_date' => now(),
                'type' => 'pengeluaran',
                'amount' => 150000,
                'description' => 'Beli Alat Kebersihan',
            ]
        ]);
    }
}