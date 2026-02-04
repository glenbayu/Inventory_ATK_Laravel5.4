<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // 1. DASHBOARD USER
    public function index()
    {
        // Panel 1: Top Barang (Sama kayak admin, user boleh tau tren)
        $topItems = Transaction::select('item_id', DB::raw('count(*) as total'))
            ->with('item')
            ->groupBy('item_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        // Panel 2: Stok Menipis (User perlu tau biar ga minta barang kosong)
        $criticalItems = Item::whereColumn('stock', '<=', 'safety_stock')->get();

        // Panel 3: Riwayat Transaksi PRIBADI (Hanya punya dia)
        // GANTI BAGIAN INI:
        $myHistory = Transaction::with('item')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('transaction_code'); // Kelompokkan

        return view('user.dashboard', compact('topItems', 'criticalItems', 'myHistory'));
    }

    // 2. HALAMAN FORM REQUEST
    public function createRequest()
    {
        // Tampilkan barang yang stoknya > 0 saja
        $items = Item::where('stock', '>', 0)->orderBy('name')->get();
        return view('user.request', compact('items'));
    }

    public function storeRequest(Request $request)
    {
        // 1. Validasi Array
        $this->validate($request, [
            'item_id.*' => 'required|exists:items,id', // Cek setiap item harus ada
            'qty.*'     => 'required|numeric|min:1',   // Cek setiap qty harus angka
            'reason'    => 'nullable|string'
        ]);

        // 2. Generate Kode Transaksi (Satu kode untuk satu rombongan barang)
        // Format: REQ-TIMESTAMP-USERID (Biar unik)
        $code = 'REQ-' . time() . '-' . Auth::id();

        // 3. Looping Simpan Data
        // Kita ambil array item_id, lalu kita putar (loop)
        $items = $request->item_id;

        // Gunakan DB Transaction biar aman (kalau satu gagal, semua batal)
        DB::transaction(function () use ($items, $request, $code) {
            foreach ($items as $key => $itemId) {
                // Lewati jika item_id kosong (jaga-jaga)
                if (empty($itemId)) continue;

                Transaction::create([
                    'user_id' => Auth::id(),
                    'item_id' => $itemId,
                    'qty'     => $request->qty[$key], // Ambil qty sesuai urutan index
                    'reason'  => $request->reason,
                    'status'  => 'pending',
                    'transaction_code' => $code
                ]);
            }
        });

        return redirect()->route('user.dashboard')->with('success', 'Permintaan borongan berhasil dikirim!');
    }
}
