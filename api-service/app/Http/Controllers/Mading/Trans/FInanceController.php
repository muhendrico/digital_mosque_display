<?php

namespace App\Http\Controllers\Mading\Trans;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FinanceController extends Controller
{
    public function index()
    {
        // Hitung Saldo Total
        $pemasukan = DB::table('finances')->where('type', 'pemasukan')->sum('amount');
        $pengeluaran = DB::table('finances')->where('type', 'pengeluaran')->sum('amount');
        $saldo = $pemasukan - $pengeluaran;

        // Ambil 5 Transaksi Terakhir (Opsional, buat running text atau list)
        $terbaru = DB::table('finances')
                    ->orderBy('transaction_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();

        return response()->json([
            'saldo' => $saldo,
            'pemasukan_total' => $pemasukan,
            'pengeluaran_total' => $pengeluaran,
            'history' => $terbaru
        ]);
    }
}