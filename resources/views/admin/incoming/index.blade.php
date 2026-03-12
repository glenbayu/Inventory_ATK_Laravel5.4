@extends('layouts.admin')

@section('title', 'Riwayat Barang Masuk')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 style="font-family: 'Roboto Mono'; font-weight: bold; border-bottom: 3px solid #333; padding-bottom: 10px;">
            RIWAYAT BARANG MASUK (STOCK IN)
        </h2>

        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-import"></i> LOG AKTIVITAS RESTOCK
            </div>
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr style="background: #222; color: #fff;">
                            <th>TANGGAL & JAM</th>
                            <th>NAMA BARANG</th>
                            <th>DIINPUT OLEH</th>
                            <th class="text-center">JUMLAH MASUK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $history)
                        <tr>
                            <td style="font-family:'Roboto Mono'">
                                {{ $history->created_at->format('d M Y') }} 
                                <small class="text-muted">{{ $history->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <b>{{ $history->item->name }}</b><br>
                                <small class="text-muted">{{ $history->item->code }}</small>
                            </td>
                            <td>
                                @if($history->user)
                                    <span class="label label-default" style="font-size: 11px;">ADMIN</span> {{ $history->user->name }}
                                @else
                                    <span class="text-muted">- User Dihapus -</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span style="font-size: 16px; font-weight: bold; color: #27ae60; font-family: 'Roboto Mono';">
                                    +{{ $history->qty }}
                                </span>
                                <small>{{ $history->item->unit }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center" style="padding: 30px;">
                                Belum ada riwayat barang masuk.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="text-center">
                    {{ $stocks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection