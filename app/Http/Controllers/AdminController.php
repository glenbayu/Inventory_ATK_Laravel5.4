<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;        // Pastikan Model Item sudah ada
use App\Transaction; // Pastikan Model Transaction sudah ada
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

        // Kirim semua data ke View
        return view('admin.dashboard', compact('criticalItems', 'pendingApprovals', 'topItems', 'chartLabels', 'chartValues'));
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
