<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\IncomingStock;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        // Kita paginate 10 barang per halaman biar rapi
        $items = Item::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:items',
            'name' => 'required',
            'stock' => 'required|numeric',
        ]);

        Item::create($request->all());
        return back()->with('success', 'Barang berhasil ditambahkan ke Gudang!');
    }

    // 2. FUNGSI UNTUK MENYIMPAN PERUBAHAN (UPDATE)
    public function update(Request $request, $id)
    {
        // Validasi input dulu
        $this->validate($request, [
            'code' => 'required|string|max:50|unique:items,code,' . $id, // Cek unik kecuali punya sendiri
            'name' => 'required|string|max:191',
            'stock' => 'required|integer|min:0',
            'category' => 'required|string',
            'unit' => 'required|string',
            'safety_stock' => 'required|integer|min:0',
        ]);

        // Cari dan update
        $item = Item::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Item::destroy($id);
        return back()->with('success', 'Barang dihapus dari sistem.');
    }

    // 1. FUNGSI UNTUK MENAMPILKAN FORM EDIT
    public function edit($id)
    {
        // Cari barang berdasarkan ID, kalau ga ketemu error 404
        $item = Item::findOrFail($id);

        // Tampilkan view edit sambil bawa data barangnya
        return view('admin.items.edit', compact('item'));
    }

    public function restock(Request $request, $id)
    {
        // 1. Validasi
        $this->validate($request, [
            'qty_add' => 'required|integer|min:1'
        ]);

        // 2. Cari Barang
        $item = Item::findOrFail($id);

        // 3. Update Otomatis (Database yang hitung)
        // Query yang jalan: UPDATE items SET stock = stock + 10 WHERE id = 1
        $item->increment('stock', $request->qty_add);

        // 3. PENCATATAN RIWAYAT (BAGIAN INI YANG KEMARIN HILANG)
        IncomingStock::create([
            'item_id' => $item->id,
            'user_id' => Auth::id(), // ID Admin yang login
            'qty'     => $request->qty_add,
        ]);

        // JANGAN ADA $item->save(); DISINI! HAPUS AJA!

        return redirect()->back()->with('success', 'Stok berhasil ditambahkan!');
    }
}
