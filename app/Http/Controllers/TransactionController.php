<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionController extends Controller
{
    public function approvalPage()
    {
        // 1. Ambil data, urutkan dari yang terbaru
        $allTrx = Transaction::with(['user', 'item'])
            ->orderBy('created_at', 'desc')
            ->get(); // Ambil Collection dulu

        // 2. Kelompokkan berdasarkan 'transaction_code'
        // Hasilnya jadi Array of Arrays: [ 'TRX-001' => [Item A, Item B], 'TRX-002' => [Item C] ]
        $groupedTransactions = $allTrx->groupBy('transaction_code');

        // Kita pakai pagination manual simpel atau kirim semua grup (sementara kirim semua biar mudah)
        return view('admin.transactions.approval', compact('groupedTransactions'));
    }
}
