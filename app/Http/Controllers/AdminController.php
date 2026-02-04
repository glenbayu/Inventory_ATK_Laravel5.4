<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Transaction;
use App\IncomingStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // 1. DATA STOK KRITIS (Panel Bawah)
        // Logic: Ambil barang dimana stok <= safety_stock
        $criticalItems = Item::whereColumn('stock', '<=', 'safety_stock')->get();

        // 2. DATA APPROVAL (Panel Kanan Atas)
        // Logic: Ambil transaksi status 'pending', load relasi user & item
        $pendingApprovals = Transaction::with(['user', 'item'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->take(5) // Ambil 5 terlama biar ga penuh
            ->get();

        // 3. DATA TOP BARANG (Panel Tengah Atas)
        // Logic: Hitung jumlah request per item
        $topItems = Transaction::select('item_id', DB::raw('count(*) as total'))
            ->with('item')
            ->groupBy('item_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // --- BARU: LOGIC TOP DEPARTEMEN ---
        // 1. Join tabel transactions ke users
        // 2. Group by department
        // 3. Hitung totalnya
        $deptStats = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select('users.department', DB::raw('count(*) as total'))
            ->groupBy('users.department')
            ->orderBy('total', 'desc')
            ->limit(5) // Ambil 5 besar saja
            ->get();

        // Pisahkan data untuk Chart.js (Array Label & Array Nilai)
        $chartLabels = $deptStats->pluck('department');
        $chartValues = $deptStats->pluck('total');

        // 1. Total Jenis Barang (Item Master)
        $totalItems = Item::count();

        $trxThisMonth = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->distinct('transaction_code') // <--- Kuncinya di sini!
            ->count('transaction_code');   // <--- Hitung kodenya saja

        // 3. Total Barang Keluar Bulan Ini (Qty Approved)
        $qtyOutMonth = Transaction::where('status', 'approved')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty');

        // 4. Total Barang Masuk Bulan Ini (Dari Riwayat Restock)
        $qtyInMonth = IncomingStock::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('qty');

        $chartMonthIn = [];
        $chartMonthOut = [];

        // Kita loop dari bulan 1 (Januari) sampai 12 (Desember)
        for ($i = 1; $i <= 12; $i++) {

            // 1. Hitung Barang Masuk (Restock) per Bulan $i
            $chartMonthIn[] = IncomingStock::whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $i)
                ->sum('qty');

            // 2. Hitung Barang Keluar (Approved) per Bulan $i
            $chartMonthOut[] = Transaction::whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $i)
                ->where('status', 'approved') // Hanya yang sukses keluar
                ->sum('qty');
        }

        // Kirim semua data ke View
        return view('admin.dashboard', compact(
            'criticalItems',
            'pendingApprovals',
            'topItems',
            'chartLabels',
            'chartValues',
            'totalItems',
            'trxThisMonth',
            'qtyOutMonth',
            'qtyInMonth',
            'chartMonthIn',
            'chartMonthOut'
        ));
    }

    public function restock($id)
    {
        $item = Item::findOrFail($id);

        $item->stock = $item->max_stock;
        $item->save();

        Session::flash('success', 'Stok untuk barang ' . $item->name . ' telah direset ke ' . $item->max_stock . '.');
        return back();
    }

    public function approve($id)
    {
        $trx = Transaction::with('item')->findOrFail($id);

        // Cek dulu, stoknya ada gak?
        if ($trx->item->stock < $trx->qty) {
            Session::flash('error', "Gagal! Stok {$trx->item->name} tidak cukup. Sisa: {$trx->item->stock}, Minta: {$trx->qty}");
            return back();
        }

        // Kalau aman, kurangi stok & ubah status
        $trx->item->decrement('stock', $trx->qty);
        $trx->status = 'approved';
        $trx->save();

        Session::flash('success', "Permintaan {$trx->transaction_code} DISETUJUI.");
        return back();
    }

    public function reject($id)
    {
        $trx = Transaction::findOrFail($id);
        $trx->status = 'rejected';
        $trx->save();

        Session::flash('warning', "Permintaan {$trx->transaction_code} DITOLAK.");
        return back();
    }

    // FITUR APPROVE ALL (BORONGAN)
    public function approveAll($code)
    {
        // 1. Ambil semua item yang masih PENDING dalam kode transaksi ini
        $transactions = Transaction::where('transaction_code', $code)
            ->where('status', 'pending')
            ->with('item') // Load data barang untuk cek stok
            ->get();

        if ($transactions->isEmpty()) {
            return back()->with('error', 'Semua item sudah diproses sebelumnya.');
        }

        // 2. SAFETY CHECK: Cek apakah SEMUA stok cukup?
        // Kalau ada 1 aja yang kurang, kita tolak aksi "Approve All" ini.
        foreach ($transactions as $trx) {
            if ($trx->item->stock < $trx->qty) {
                return back()->with('error', "Gagal Approve All! Stok barang '{$trx->item->name}' tidak cukup (Sisa: {$trx->item->stock}, Minta: {$trx->qty}). Harap proses manual satu per satu.");
            }
        }

        // 3. EKSEKUSI (Karena sudah lolos cek stok)
        // Pakai DB Transaction biar aman (kalau error di tengah, rollback semua)
        DB::transaction(function () use ($transactions) {
            foreach ($transactions as $trx) {
                // Kurangi stok
                $trx->item->decrement('stock', $trx->qty);
                // Update status
                $trx->status = 'approved';
                $trx->save();
            }
        });

        return back()->with('success', "Sukses! Semua permintaan dalam kode $code berhasil disetujui.");
    }
}
