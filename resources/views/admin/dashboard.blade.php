@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<style>
    .pending-approval-wrap {
        overflow: hidden;
    }

    .pending-approval-wrap.is-scroll {
        max-height: 100px; /* kira-kira tinggi untuk 2 transaksi */
        overflow-y: auto;
    }

    .pending-approval-footer {
        border-top: 1px solid #e7edf2;
        padding: 10px 12px;
        text-align: right;
        background: #fafcfd;
    }
</style>

<h2>DASHBOARD ADMIN</h2>

@if(Session::has('success'))
<div class="alert alert-success">
    <strong>SUKSES:</strong> {{ Session::get('success') }}
</div>
@endif

<div class="row">

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body metric-card">
                <div class="metric-icon">
                    <i class="glyphicon glyphicon-th-large"></i>
                </div>
                <div>
                    <div class="metric-label">Jenis Barang</div>
                    <div class="metric-value">
                        {{ $totalItems }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body metric-card">
                <div class="metric-icon" style="color: #d88a14;">
                    <i class="glyphicon glyphicon-transfer"></i>
                </div>
                <div>
                    <div class="metric-label">Transaksi (Bln)</div>
                    <div class="metric-value">
                        {{ $trxThisMonth }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body metric-card">
                <div class="metric-icon" style="color: #b4382a;">
                    <i class="glyphicon glyphicon-export"></i>
                </div>
                <div>
                    <div class="metric-label">Qty Keluar</div>
                    <div class="metric-value">
                        {{ $qtyOutMonth }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body metric-card">
                <div class="metric-icon" style="color: #2b8a57;">
                    <i class="glyphicon glyphicon-import"></i>
                </div>
                <div>
                    <div class="metric-label">Qty Masuk</div>
                    <div class="metric-value">
                        {{ $qtyInMonth }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-stats"></i> STATISTIK ARUS BARANG (TAHUN {{ date('Y') }})
            </div>
            <div class="panel-body" style="height: 300px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-8">
        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-stats"></i> Dept. Permintaan
            </div>
            <div class="panel-body" style="height: 360px; padding: 15px;">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">

        <div class="panel panel-industrial" style="margin-bottom: 20px;">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-bell"></i> Menunggu Approval
            </div>
            <div class="panel-body" style="padding: 0;">
                @php $isPendingScrollable = $pendingApprovalGroups->count() > 2; @endphp
                <div class="pending-approval-wrap {{ $isPendingScrollable ? 'is-scroll' : '' }}">
                <table class="table table-hover" style="margin-bottom: 0;">
                    @forelse($pendingApprovalGroups as $code => $group)
                    @php
                        $first = $group->first();
                        $modalID = md5($code);
                    @endphp
                    <tr>
                        <td>
                            <small class="text-muted" style="font-family:'Roboto Mono'">{{ $code }}</small><br>
                            <b>{{ $first->user->name }}</b> ({{ $first->user->department }})<br>
                            <small class="text-muted">{{ $group->count() }} item | Total qty: {{ $group->sum('qty') }}</small>
                        </td>
                        <td>
                            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalTrx{{ $modalID }}">
                                CEK
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center" style="padding: 20px;">Semua aman!</td>
                    </tr>
                    @endforelse
                </table>
                </div>
                <div class="pending-approval-footer">
                    <a href="{{ route('admin.transactions.approval') }}" class="btn btn-default btn-sm">
                        LIHAT SEMUA TRANSAKSI
                    </a>
                </div>
            </div>
        </div>

        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-star"></i> Top Barang Keluar
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Barang</th>
                        <th class="text-right">Total Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topItems as $top)
                    <tr>
                        <td>{{ $top->item->name }}</td>
                        <td class="text-right data-number">{{ $top->total }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" class="text-center">- Belum ada data -</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-industrial" style="border-top-color: #b4382a;">
            <div class="panel-heading-industrial" style="background: #f6e9e7; color: #7d241b;">
                <i class="glyphicon glyphicon-alert"></i> WARNING: STOK BARANG MENIPIS
            </div>
            
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Barang</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center">Min</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($criticalItems as $item)
                            <tr style="background-color: #fff8f7;">
                                <td class="data-number">{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-center data-number" style="color: #a0261a; font-size: 16px;">{{ $item->stock }}</td>
                                <td class="text-center data-number">{{ $item->safety_stock }}</td>
                                <td class="text-center"><span class="badge" style="background: #b4382a;">KRITIS</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-success"
                                        data-toggle="modal"
                                        data-target="#modalRestock{{ $item->id }}">
                                        <i class="glyphicon glyphicon-plus"></i> RESTOCK
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Stok aman terkendali.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($pendingApprovalGroups as $code => $group)
@php
    $first = $group->first();
    $modalID = md5($code);
@endphp
<div id="modalTrx{{ $modalID }}" class="modal fade modal-industrial" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">APPROVAL: {{ $code }}</h4>
            </div>
            <div class="modal-body">
                <p><b>User:</b> {{ $first->user->name }} ({{ $first->user->department }})</p>
                <p><b>Total Item:</b> {{ $group->count() }} | <b>Total Qty:</b> {{ $group->sum('qty') }}</p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Barang</th>
                                <th class="text-center">Qty Minta</th>
                                <th class="text-center">Stok Gudang</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group as $trx)
                            <tr>
                                <td>{{ $trx->item ? $trx->item->name : 'Item Dihapus' }}</td>
                                <td class="text-center">{{ $trx->qty }}</td>
                                <td class="text-center">{{ $trx->item ? $trx->item->stock : 0 }}</td>
                                <td class="text-center">
                                    @if($trx->item && $trx->item->stock >= $trx->qty)
                                    <form action="{{ route('admin.trx.approve', $trx->id) }}" method="POST" style="display:inline;">
                                        {{ csrf_field() }}
                                        <button class="btn btn-success btn-xs">SETUJUI</button>
                                    </form>
                                    @else
                                    <button disabled class="btn btn-default btn-xs">STOK KURANG</button>
                                    @endif

                                    <form action="{{ route('admin.trx.reject', $trx->id) }}" method="POST" style="display:inline;">
                                        {{ csrf_field() }}
                                        <button class="btn btn-danger btn-xs">TOLAK</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <form action="{{ route('admin.trx.approveAll', $code) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-success">SETUJUI SEMUA</button>
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($criticalItems as $item)
<div id="modalRestock{{ $item->id }}" class="modal fade modal-industrial" role="dialog">
    <div class="modal-dialog modal-sm" style="margin-top: 10%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="glyphicon glyphicon-import"></i> RESTOCK</h4>
            </div>
            <form action="{{ route('admin.item.restock', $item->id) }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label>Barang:</label>
                        <input type="text" class="form-control" value="{{ $item->name }}" readonly style="background: #eee;">
                    </div>
                    <div class="form-group">
                        <label>Tambah Qty:</label>
                        <input type="number" name="qty_add" class="form-control" placeholder="0" required min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
    // SCRIPT CHART (TIDAK BERUBAH)
    var ctx = document.getElementById("deptChart").getContext('2d');
    var labels = {!!json_encode($chartLabels) !!};
    var data = {!!json_encode($chartValues) !!};

    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Permintaan',
                data: data,
                backgroundColor: '#d88a14',
                borderColor: '#bf7810',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }],
                xAxes: [{ gridLines: { display: false } }]
            }
        }
    });

    var ctxTrend = document.getElementById("trendChart").getContext('2d');
    var trendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
            datasets: [
                {
                    label: "Barang Masuk",
                    data: {!!json_encode($chartMonthIn) !!},
                    borderColor: '#2b8a57',
                    backgroundColor: 'rgba(43, 138, 87, 0.10)',
                    borderWidth: 2,
                    pointRadius: 3
                },
                {
                    label: "Barang Keluar",
                    data: {!!json_encode($chartMonthOut) !!},
                    borderColor: '#b4382a',
                    backgroundColor: 'rgba(180, 56, 42, 0.10)',
                    borderWidth: 2,
                    pointRadius: 3
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                yAxes: [{ ticks: { beginAtZero: true } }],
                xAxes: [{ gridLines: { display: false } }]
            },
            tooltips: { mode: 'index', intersect: false }
        }
    });
</script>
@endsection
