@extends('layouts.admin')

@section('title', 'Dashboard User')

@section('content')

<div class="row" style="margin-bottom: 30px; border-bottom: 4px solid #333; padding-bottom: 20px;">
    <div class="col-md-8">
        <h2 style="font-family: 'Roboto Mono'; font-weight: bold; margin: 0 0 15px 0; color: #222;">
            HALO, {{ strtoupper(Auth::user()->name) }}
        </h2>
        <span class="label label-default" style="border-radius: 0; font-size: 12px; background: #555; padding: 8px 12px;">
            DEPT: {{ strtoupper(Auth::user()->department) }}
        </span>
    </div>

    <div class="col-md-4 text-right" style="padding-top: 5px;">
        <a href="{{ route('user.request.create') }}" class="btn btn-warning btn-lg btn-industrial" style="background-color: #f39c12; color: #000; box-shadow: 5px 5px 0px #333;">
            <i class="glyphicon glyphicon-plus"></i> BUAT PERMINTAAN BARU
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">

        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-stats"></i> TREN BARANG
            </div>
            <table class="table table-striped table-condensed">
                @forelse($topItems as $top)
                <tr>
                    <td>
                        @if($top->item)
                        <small>{{ $top->item->name }}</small>
                        @else
                        <small class="text-danger"><i>(Item Dihapus)</i></small>
                        @endif
                    </td>
                    <td class="text-right">
                        <b>{{ $top->total }}</b>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center text-muted"><small>- Data kosong -</small></td>
                </tr>
                @endforelse
            </table>
        </div>

        <div class="panel panel-industrial" style="border-color: #c0392b;">
            <div class="panel-heading-industrial" style="background: #c0392b; color: #fff; border-bottom: 2px solid #000;">
                <i class="glyphicon glyphicon-alert"></i> STOK MENIPIS
            </div>
            <ul class="list-group">
                @forelse($criticalItems as $crit)
                <li class="list-group-item" style="border-radius:0; padding: 10px;">
                    <span class="badge pull-right" style="background:#c0392b; border-radius:0">{{ $crit->stock }}</span>
                    <b>{{ $crit->name }}</b>
                </li>
                @empty
                <li class="list-group-item text-center text-muted"><small>Semua stok aman.</small></li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel panel-industrial" style="min-height: 400px;">
            <div class="panel-heading-industrial" style="background: #222; border-bottom: 4px solid #f39c12;">
                <i class="glyphicon glyphicon-time"></i> RIWAYAT PERMINTAAN SAYA
            </div>
            <div class="panel-body" style="padding: 0;">
                <table class="table table-hover table-striped mb-0">
                    <thead style="background: #ddd;">
                        <tr>
                            <th>TANGGAL</th>
                            <th>KODE TRX</th>
                            <th>TOTAL BARANG</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">DETAIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($myHistory as $code => $group)
                        @php
                        $first = $group->first();
                        $modalID = md5($code); // ID Unik Modal
                        @endphp
                        <tr>
                            <td style="vertical-align: middle;">{{ $first->created_at->format('d/m/Y') }}</td>
                            <td style="font-family:'Roboto Mono'; font-size:12px; vertical-align: middle;">
                                {{ $code }}
                            </td>
                            <td style="vertical-align: middle;">
                                {{ $group->count() }} Item
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                @if($group->contains('status', 'pending'))
                                <span class="label label-warning" style="border-radius:0; padding: 5px;">PROSES</span>
                                @else
                                <span class="label label-success" style="border-radius:0; padding: 5px;">SELESAI</span>
                                @endif
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <button class="btn btn-xs btn-info btn-industrial" data-toggle="modal" data-target="#modal-{{ $modalID }}">
                                    LIHAT
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding: 50px;">
                                <h4 class="text-muted" style="font-family: 'Roboto Mono'">BELUM ADA DATA</h4>
                                <p>Silakan buat permintaan barang baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($myHistory as $code => $group)
@php
$modalID = md5($code);
@endphp
<div id="modal-{{ $modalID }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:0; border:3px solid #333;">
            <div class="modal-header" style="background:#222; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff">&times;</button>
                <h4 class="modal-title" style="font-family:'Roboto Mono'">DETAIL: {{ $code }}</h4>
            </div>
            <div class="modal-body" style="padding: 0;">
                <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                    <thead style="background: #eee;">
                        <tr>
                            <th>Barang</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group as $item)
                        <tr>
                            <td>
                                @if($item->item)
                                {{ $item->item->name }}
                                @else
                                <span class="text-danger">Item Dihapus</span>
                                @endif
                            </td>
                            <td width="50" class="text-center" style="font-weight: bold;">
                                {{ $item->qty }}
                            </td>
                            <td width="100" class="text-center">
                                @if($item->status == 'pending')
                                <span class="label label-warning">MENUNGGU</span>
                                @elseif($item->status == 'approved')
                                <span class="label label-success">DISETUJUI</span>
                                @else
                                <span class="label label-danger">DITOLAK</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($group->first()->reason)
                <div style="padding: 15px; background: #fafafa; border-top: 1px solid #ddd;">
                    <b>Catatan:</b> <br> {{ $group->first()->reason }}
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-industrial" data-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection