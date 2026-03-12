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
    public function createRequest(Request $request)
    {
        $items = Item::where('stock', '>', 0)
            ->orderBy('name', 'asc')
            ->get();

        return view('user.request.create', compact('items'));
    }

    public function storeRequest(Request $request)
    {
        // 1. Validasi Input
        $this->validate($request, [
            'item_id' => 'required|array',      // Harus array karena barang banyak
            'item_id.*' => 'exists:items,id',   // Pastikan barangnya ada
            'qty' => 'required|array',          
            'qty.*' => 'integer|min:1',         // Minimal minta 1
            'department' => 'required|string',  // Wajib pilih departemen
            'reason' => 'nullable|string'       // Alasan boleh kosong
        ]);

        // 2. Bikin Kode Transaksi Unik (REQ-TANGGAL-RANDOM)
        // Contoh: REQ-06022026-X7Z
        $code = 'REQ-' . date('dmY') . '-' . strtoupper(str_random(3));

        // 3. Simpan Setiap Barang ke Database
        // Kita looping array item_id yang dikirim dari form
        foreach ($request->item_id as $key => $id) {
            
            // Cek stok dulu biar aman (Validasi Server Side)
            $item = \App\Item::find($id);
            $qty_minta = $request->qty[$key];

            if($item->stock < $qty_minta) {
                return back()->with('error', "Stok barang {$item->name} tidak cukup! Sisa: {$item->stock}");
            }

            // Simpan Transaksi
            \App\Transaction::create([
                'user_id' => \Auth::id(),
                'item_id' => $id,
                'qty' => $qty_minta,
                'department' => $request->department, // <--- Simpan Dept Manual
                'reason' => $request->reason,         // <--- Simpan Alasan
                'status' => 'pending',
                'transaction_code' => $code
            ]);
        }

        return redirect()->route('user.dashboard')->with('success', 'Permintaan berhasil diajukan! Kode: ' . $code);
    }
}
