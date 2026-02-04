@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<h2 style="font-weight: bold; border-bottom: 3px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
    DASHBOARD ADMIN
</h2>

@if(Session::has('success'))
<div class="alert alert-success" style="border-left: 5px solid green;">
    <strong>SUKSES:</strong> {{ Session::get('success') }}
</div>
@endif

<div class="row" style="margin-bottom: 20px;">

    <div class="col-md-3">
        <div class="panel panel-industrial" style="border-top: 4px solid #222;">
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
        <div class="panel panel-industrial" style="border-top: 4px solid #f39c12;">
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
        <div class="panel panel-industrial" style="border-top: 4px solid #c0392b;">
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
        <div class="panel panel-industrial" style="border-top: 4px solid #27ae60;">
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
            <div class="panel-heading-industrial" style="background: #222; border-bottom: 2px solid #777;">
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

        <div class="panel panel-industrial" style="border-color: #f39c12; margin-bottom: 20px;">
            <div class="panel-heading-industrial" style="background: #f39c12; color: #000;">
                <i class="glyphicon glyphicon-bell"></i> Menunggu Approval
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
                            <button type="button" class="btn btn-xs btn-primary btn-industrial" data-toggle="modal" data-target="#modalTrx{{ $trx->id }}">
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
        <div class="panel panel-industrial" style="border-color: #c0392b;">
            <div class="panel-heading-industrial" style="background: #c0392b;">
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
                            <tr style="background-color: #fff0f0;">
                                <td class="data-number">{{ $item->code }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-center data-number" style="color: red; font-size: 16px;">{{ $item->stock }}</td>
                                <td class="text-center data-number">{{ $item->safety_stock }}</td>
                                <td class="text-center"><span class="badge bg-danger" style="border-radius:0;">KRITIS</span></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-success btn-industrial"
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
        <div class="modal-content" style="border-radius: 0; border: 3px solid #333;">
            <div class="modal-header" style="background: #222; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
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
                    <button class="btn btn-success btn-industrial">SETUJUI</button>
                </form>
                @else
                <button disabled class="btn btn-default">STOK KURANG</button>
                @endif

                <form action="{{ route('admin.trx.reject', $trx->id) }}" method="POST" style="display:inline;">
                    {{ csrf_field() }}
                    <button class="btn btn-danger btn-industrial pull-left">TOLAK</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@foreach($criticalItems as $item)
<div id="modalRestock{{ $item->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" style="margin-top: 10%;">
        <div class="modal-content" style="border-radius: 0; border: 3px solid #27ae60;">

            <div class="modal-header" style="background: #222; color: #27ae60; border-bottom: 2px solid #27ae60;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                <h4 class="modal-title" style="font-family: 'Roboto Mono'; font-weight: bold;">
                    <i class="glyphicon glyphicon-import"></i> RESTOCK BARANG
                </h4>
            </div>

            <form action="{{ route('admin.item.restock', $item->id) }}" method="POST">
                {{ csrf_field() }}

                <div class="modal-body" style="background: #fff;">
                    <div class="form-group">
                        <label>Nama Barang:</label>
                        <input type="text" class="form-control" value="{{ $item->name }}" readonly style="background: #eee; font-weight:bold;">
                    </div>

                    <div class="row">
                        <div class="col-xs-6">
                            <label>Stok Skrg:</label>
                            <div style="font-size: 18px; font-weight: bold; color: #c0392b;">
                                {{ $item->stock }} <small style="color:#333">{{ $item->unit }}</small>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <label>Minim:</label>
                            <div style="font-size: 18px; font-weight: bold; color: #333;">
                                {{ $item->safety_stock }} <small>{{ $item->unit }}</small>
                            </div>
                        </div>
                    </div>
                    <hr style="border-top: 1px dashed #333;">

                    <div class="form-group">
                        <label style="color: #27ae60; font-weight: bold;">TAMBAH BERAPA?</label>
                        <div class="input-group">
                            <input type="number" name="qty_add" class="form-control input-lg" placeholder="0" required min="1" autofocus style="font-family: 'Roboto Mono'; font-weight: bold;">
                            <span class="input-group-addon" style="background: #222; color: #fff; border: 1px solid #222;">{{ $item->unit }}</span>
                        </div>
                        <small class="text-muted">*Input jumlah yang baru dibeli/masuk.</small>
                    </div>
                </div>

                <div class="modal-footer" style="background: #222; border-top: 2px solid #27ae60;">
                    <button type="button" class="btn btn-default btn-industrial pull-left" data-dismiss="modal">BATAL</button>
                    <button type="submit" class="btn btn-success btn-industrial" style="background: #27ae60; color: #fff; border-color: #219150;">
                        SIMPAN STOK
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
    var ctx = document.getElementById("deptChart").getContext('2d');

    // Data dari Controller
    var labels = {!!json_encode($chartLabels) !!};
    var data = {!!json_encode($chartValues) !!};

    var myChart = new Chart(ctx, {
        type: 'bar', // <--- KITA UBAH JADI 'bar' (BATANG)
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Permintaan',
                data: data,
                backgroundColor: [
                    '#f39c12', // Kuning Safety
                    '#222222', // Hitam Logam
                    '#7f8c8d', // Abu Beton
                    '#c0392b', // Merah Bata
                    '#2c3e50', // Biru Dongker
                    '#27ae60', // Hijau Sukses
                    '#d35400', // Oranye
                    '#8e44ad' // Ungu
                ],
                borderColor: '#000', // Garis tepi hitam tegas
                borderWidth: 2
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                display: false // Kita sembunyikan legend karena nama dept sudah ada di bawah batang
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true, // Mulai dari angka 0
                        stepSize: 1, // Kelipatan 1 (biar ga ada angka desimal 1.5 permintaan)
                        fontFamily: "'Roboto Mono', monospace" // Font angka industrial
                    },
                    gridLines: {
                        color: "rgba(0, 0, 0, 0.1)" // Garis bantu tipis
                    }
                }],
                xAxes: [{
                    ticks: {
                        fontFamily: "'Roboto Mono', monospace", // Font label departemen
                        fontWeight: 'bold'
                    },
                    gridLines: {
                        display: false // Hilangkan garis vertikal biar bersih
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.yLabel + " Permintaan";
                    }
                },
                backgroundColor: '#222',
                titleFontFamily: "'Roboto Mono'",
                bodyFontFamily: "'Roboto Mono'",
                cornerRadius: 0 // Tooltip kotak tajam
            }
        }
    });
</script>
<script>
    var ctxTrend = document.getElementById("trendChart").getContext('2d');

    var trendChart = new Chart(ctxTrend, {
        type: 'line', // Tipe Grafik Garis
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
            datasets: [{
                    label: "Barang Masuk (Restock)",
                    data: {!!json_encode($chartMonthIn) !!},
                    borderColor: '#27ae60', // Warna Hijau
                    backgroundColor: 'rgba(39, 174, 96, 0.1)', // Hijau Transparan
                    borderWidth: 3,
                    pointBackgroundColor: '#27ae60',
                    pointRadius: 4
                },
                {
                    label: "Barang Keluar (Terpakai)",
                    data: {!!json_encode($chartMonthOut) !!},
                    borderColor: '#c0392b', // Warna Merah
                    backgroundColor: 'rgba(192, 57, 43, 0.1)', // Merah Transparan
                    borderWidth: 3,
                    pointBackgroundColor: '#c0392b',
                    pointRadius: 4
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontFamily: "'Roboto Mono'"
                    },
                    gridLines: {
                        color: "rgba(0,0,0,0.05)"
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        fontFamily: "'Roboto Mono'"
                    }
                }]
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                titleFontFamily: "'Roboto Mono'",
                bodyFontFamily: "'Roboto Mono'"
            }
        }
    });
</script>
@endsection