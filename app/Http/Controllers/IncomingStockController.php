<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\IncomingStock; // Import Model

class IncomingStockController extends Controller
{
    public function index()
    {
        // Ambil data riwayat, urutkan dari yang terbaru
        // 'with' gunanya biar kita bisa ambil nama barang & nama user
        $stocks = IncomingStock::with(['item', 'user'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.incoming.index', compact('stocks'));
    }
}