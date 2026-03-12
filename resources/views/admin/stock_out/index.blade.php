@extends('layouts.admin')

@section('title', 'Riwayat Barang Keluar')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2>RIWAYAT BARANG KELUAR (STOCK OUT)</h2>

        <div class="panel panel-industrial" style="border-top-color: #b4382a;">
            <div class="panel-heading-industrial" style="background: #f6e9e7; color: #7d241b;">
                <i class="glyphicon glyphicon-export"></i> LOG BARANG KELUAR (APPROVED)
            </div>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>TANGGAL KELUAR</th>
                            <th>KODE TRANSAKSI</th>
                            <th>BARANG</th>
                            <th>PEMINTA (USER)</th>
                            <th class="text-center">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        <tr>
                            <td>
                                {{ $trx->updated_at->format('d M Y') }} <br>
                                <small class="text-muted">{{ $trx->updated_at->format('H:i') }}</small>
                            </td>
                            <td style="font-weight:700; color: #b4382a;">
                                {{ $trx->transaction_code }}
                            </td>
                            <td>
                                <b>{{ $trx->item->name }}</b><br>
                                <small class="text-muted">{{ $trx->item->code }}</small>
                            </td>
                            <td>
                                <b>{{ $trx->user->name }}</b><br>
                                <span class="label label-default">{{ $trx->user->department }}</span>
                            </td>
                            <td class="text-center">
                                <span style="font-size: 16px; font-weight: bold; color: #c0392b; font-family: 'Roboto Mono';">
                                    -{{ $trx->qty }}
                                </span>
                                <small>{{ $trx->item->unit }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 30px;">
                                Belum ada barang keluar yang disetujui.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="text-center">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
