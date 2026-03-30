<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use PDF;   // Pakai Alias
use Excel; // Pakai Alias

class ReportController extends Controller
{
    private function getFilteredTransactions($request)
    {
        $query = Transaction::with(['user', 'item'])->orderBy('created_at', 'desc');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        return $query->get();
    }

    public function index(Request $request)
    {
        // 1. Ambil data mentah (semua baris barang)
        $raw_data = $this->getFilteredTransactions($request);

        // 2. Kelompokkan berdasarkan Kode Transaksi khusus untuk Tampilan Web
        // Hasilnya: [ 'TRX-001' => [Item A, Item B], 'TRX-002' => [Item C] ]
        $grouped_transactions = $raw_data->groupBy('transaction_code');

        // 3. Kirim data yang sudah dikelompokkan ke View
        return view('admin.reports.index', compact('grouped_transactions', 'raw_data'));
    }

    public function exportPdf(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);

        // PDF::loadView sekarang pasti jalan karena pakai 'use PDF;'
        $pdf = PDF::loadView('admin.reports.pdf_view', compact('transactions'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-' . date('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $transactions = $this->getFilteredTransactions($request);
        $namaFile = 'laporan-transaksi-' . date('Y-m-d');

        // Excel::create sekarang pasti jalan karena pakai 'use Excel;'
        return Excel::create($namaFile, function ($excel) use ($transactions) {

            $excel->sheet('Data Transaksi', function ($sheet) use ($transactions) {
                $sheet->loadView('admin.reports.excel_view', ['transactions' => $transactions]);
            });
        })->download('xlsx');
    }
}
