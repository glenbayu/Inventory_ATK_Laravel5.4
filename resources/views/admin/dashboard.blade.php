@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<h2 style="font-weight: bold; padding-bottom: 10px; margin-bottom: 20px;">
    DASHBOARD ADMIN
</h2>

@if(Session::has('success'))
<div class="alert alert-success" style="border-left: 5px solid green;">
    <strong>SUKSES:</strong> {{ Session::get('success') }}
</div>
@endif

<div class="row" style="margin-bottom: 20px;">

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body" style="padding: 15px; display: flex; align-items: center;">
                <div style="font-size: 40px; color: #222; margin-right: 15px;">
                    <i class="glyphicon glyphicon-th-large"></i>
                </div>
                <div>
                    <div style="font-size: 12px; font-weight: bold; color: #777; text-transform: uppercase;">Jenis Barang</div>
                    <div style="font-size: 28px; font-weight: bold; font-family: 'Roboto Mono', monospace; color: #222;">
                        {{ $totalItems }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body" style="padding: 15px; display: flex; align-items: center;">
                <div style="font-size: 40px; color: #f39c12; margin-right: 15px;">
                    <i class="glyphicon glyphicon-transfer"></i>
                </div>
                <div>
                    <div style="font-size: 12px; font-weight: bold; color: #777; text-transform: uppercase;">Transaksi (Bln)</div>
                    <div style="font-size: 28px; font-weight: bold; font-family: 'Roboto Mono', monospace; color: #222;">
                        {{ $trxThisMonth }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body" style="padding: 15px; display: flex; align-items: center;">
                <div style="font-size: 40px; color: #c0392b; margin-right: 15px;">
                    <i class="glyphicon glyphicon-export"></i>
                </div>
                <div>
                    <div style="font-size: 12px; font-weight: bold; color: #777; text-transform: uppercase;">Qty Keluar</div>
                    <div style="font-size: 28px; font-weight: bold; font-family: 'Roboto Mono', monospace; color: #222;">
                        {{ $qtyOutMonth }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel panel-industrial">
            <div class="panel-body" style="padding: 15px; display: flex; align-items: center;">
                <div style="font-size: 40px; color: #27ae60; margin-right: 15px;">
                    <i class="glyphicon glyphicon-import"></i>
                </div>
                <div>
                    <div style="font-size: 12px; font-weight: bold; color: #777; text-transform: uppercase;">Qty Masuk</div>
                    <div style="font-size: 28px; font-weight: bold; font-family: 'Roboto Mono', monospace; color: #222;">
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
            <div class="panel-body" style="height: 350px; padding: 15px;">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">

        <div class="panel panel-industrial" style="margin-bottom: 20px;">
            <div class="panel-heading-industrial" style="background: #f39c12; color: #fff;">
                <i class="glyphicon glyphicon-bell" style="color: #fff;"></i> Menunggu Approval
            </div>
            <div class="panel-body" style="padding: 0;">
                <table class="table table-hover">
                    @forelse($pendingApprovals as $trx)
                    <tr>
                        <td>
                            <small class="text-muted" style="font-family:'Roboto Mono'">{{ $trx->transaction_code }}</small><br>
                            <b>{{ $trx->user->name }}</b> ({{ $trx->user->department }})<br>
                            Minta: {{ $trx->item->name }} <span class="data-number">x{{ $trx->qty }}</span>
                        </td>
                        <td style="vertical-align: middle;">
                            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#modalTrx{{ $trx->id }}">
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
        <div class="panel panel-industrial" style="border-top: 3px solid #c0392b;"> <div class="panel-heading-industrial" style="background: #c0392b; color: #fff;">
                <i class="glyphicon glyphicon-alert" style="color: #fff;"></i> WARNING: STOK BARANG MENIPIS
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
                            <tr style="background-color: #fff0f0;">
                                <td class="data-number">{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-center data-number" style="color: red; font-size: 16px;">{{ $item->stock }}</td>
                                <td class="text-center data-number">{{ $item->safety_stock }}</td>
                                <td class="text-center"><span class="badge" style="background: #c0392b;">KRITIS</span></td>
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

@foreach($pendingApprovals as $trx)
<div id="modalTrx{{ $trx->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">APPROVAL: {{ $trx->transaction_code }}</h4>
            </div>
            <div class="modal-body">
                <p><b>User:</b> {{ $trx->user->name }}</p>
                <p><b>Barang:</b> {{ $trx->item->name }} (Minta: {{ $trx->qty }})</p>
                <p><b>Stok Gudang:</b> {{ $trx->item->stock }}</p>
                <hr>
                @if($trx->item->stock >= $trx->qty)
                <form action="{{ route('admin.trx.approve', $trx->id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    <button class="btn btn-success">SETUJUI</button>
                </form>
                @else
                <button disabled class="btn btn-default">STOK KURANG</button>
                @endif

                <form action="{{ route('admin.trx.reject', $trx->id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    <button class="btn btn-danger pull-left">TOLAK</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@foreach($criticalItems as $item)
<div id="modalRestock{{ $item->id }}" class="modal fade" role="dialog">
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
                backgroundColor: ['#f39c12', '#2c3e50', '#7f8c8d', '#c0392b', '#27ae60', '#8e44ad'],
                borderColor: '#eee',
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
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4
                },
                {
                    label: "Barang Keluar",
                    data: {!!json_encode($chartMonthOut) !!},
                    borderColor: '#c0392b',
                    backgroundColor: 'rgba(192, 57, 43, 0.1)',
                    borderWidth: 3,
                    pointRadius: 4
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