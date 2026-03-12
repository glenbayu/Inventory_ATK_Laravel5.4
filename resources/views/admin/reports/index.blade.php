@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 style="font-family: 'Roboto Mono'; font-weight: bold; border-bottom: 3px solid #333; padding-bottom: 10px;">
            LAPORAN TRANSAKSI (PER KODE)
        </h2>

        <div class="panel panel-industrial">
            <div class="panel-body" style="background: #eee; border-bottom: 2px solid #333;">
                <form action="{{ route('admin.reports.index') }}" method="GET" class="form-inline">
                    <div class="form-group">
                        <label>Dari:</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                    <div class="form-group" style="margin-left: 10px;">
                        <label>Sampai:</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                    <div class="form-group" style="margin-left: 10px;">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="all">-- Semua Status --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-industrial" style="margin-left: 10px;">
                        <i class="glyphicon glyphicon-filter"></i> FILTER
                    </button>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-default btn-industrial">RESET</a>
                </form>
            </div>
        </div>

        @if(isset($raw_data) && $raw_data->count() > 0)
        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.reports.pdf', request()->all()) }}" class="btn btn-danger btn-lg btn-industrial">
                <i class="glyphicon glyphicon-file"></i> EXPORT PDF (FULL)
            </a>
            <a href="{{ route('admin.reports.excel', request()->all()) }}" class="btn btn-success btn-lg btn-industrial">
                <i class="glyphicon glyphicon-list-alt"></i> EXPORT EXCEL (FULL)
            </a>
        </div>
        @endif

        <div class="panel panel-industrial">
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead style="background: #222; color: #fff;">
                        <tr>
                            <th>TANGGAL</th>
                            <th>KODE TRX</th>
                            <th>USER / DEPT</th>
                            <th class="text-center">JMLH BARANG</th>
                            <th class="text-center">STATUS</th> <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grouped_transactions as $code => $group)
                        
                        @php $firstItem = $group->first(); @endphp

                        <tr>
                            <td style="vertical-align: middle;">{{ $firstItem->created_at->format('d/m/Y') }}</td>
                            <td style="font-family:'Roboto Mono'; font-weight:bold; vertical-align: middle;">{{ $code }}</td>
                            <td style="vertical-align: middle;">
                                <b>{{ $firstItem->user->name }}</b><br>
                                <small class="text-muted">{{ $firstItem->user->department }}</small>
                            </td>
                            <td class="text-center" style="font-size: 16px; font-weight: bold; vertical-align: middle;">
                                {{ $group->count() }} <small style="font-size: 10px; font-weight: normal;">Item</small>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                @if($group->contains('status', 'pending'))
                                    <span class="label label-warning">PENDING</span>
                                @elseif($group->contains('status', 'rejected'))
                                    <span class="label label-danger">REJECTED</span>
                                @else
                                    <span class="label label-success">APPROVED</span>
                                @endif
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <button type="button" class="btn btn-info btn-sm btn-industrial" data-toggle="modal" data-target="#modal-{{ str_slug($code) }}">
                                    <i class="glyphicon glyphicon-eye-open"></i> LIHAT BARANG
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@foreach($grouped_transactions as $code => $group)
<div id="modal-{{ str_slug($code) }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 0; border: 3px solid #333;">
            <div class="modal-header" style="background: #eee; border-bottom: 2px solid #333;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-family: 'Roboto Mono'; font-weight: bold;">
                    DETAIL: {{ $code }}
                </h4>
            </div>
            <div class="modal-body">
                <table class="table table-condensed table-bordered">
                    <thead>
                        <tr style="background: #222; color: #fff;">
                            <th>NAMA BARANG</th>
                            <th>QTY</th>
                            <th>UNIT</th>
                            <th>STATUS ITEM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group as $item)
                        <tr>
                            <td>{{ $item->item->name }}</td>
                            <td class="text-center font-bold">{{ $item->qty }}</td>
                            <td>{{ $item->item->unit }}</td>
                            <td class="text-center">
                                @if($item->status == 'approved') <span class="text-success font-bold">OK</span>
                                @elseif($item->status == 'pending') <span class="text-warning font-bold">WAIT</span>
                                @else <span class="text-danger font-bold">NO</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-industrial" data-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection