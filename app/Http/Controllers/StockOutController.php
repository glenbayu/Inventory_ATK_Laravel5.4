<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction; // <-- Kita ambil dari Tabel Transaksi

class StockOutController extends Controller
{
    public function index()
    {
        // LOGIC PENTING:
        // Kita cuma ambil transaksi yang statusnya 'approved' (Sukses Keluar)
        // Kita urutkan dari yang terbaru (descending)
        $transactions = Transaction::with(['item', 'user'])
                            ->where('status', 'approved') 
                            ->orderBy('updated_at', 'desc') // Diurutkan berdasarkan tgl disetujui
                            ->paginate(10);

        return view('admin.stock_out.index', compact('transactions'));
    }
}