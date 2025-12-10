<?php

namespace App\Http\Controllers\Mading\Trans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Finance;

class FinanceController extends Controller
{
    public function index()
    {
        // Ambil data urut dari yang terbaru
        $finances = Finance::orderBy('transaction_date', 'desc')->get();
        
        // Hitung Saldo
        $pemasukan = Finance::where('type', 'pemasukan')->sum('amount');
        $pengeluaran = Finance::where('type', 'pengeluaran')->sum('amount');
        $saldo = $pemasukan - $pengeluaran;

        return view('admin.mading.trans.finances.index', compact('finances', 'saldo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:pemasukan,pengeluaran',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        Finance::create($request->all());

        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }

    public function destroy($id)
    {
        Finance::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data dihapus!');
    }
}