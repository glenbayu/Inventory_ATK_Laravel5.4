@extends('layouts.admin')

@section('title', 'Persetujuan Barang')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 style="font-family: 'Roboto Mono'; font-weight: bold; border-bottom: 3px solid #333; padding-bottom: 10px;">
            DAFTAR PERMINTAAN BARANG
        </h2>

        <div class="panel panel-industrial">
            <div class="panel-body">

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr style="background: #222; color: #fff;">
                            <th>TANGGAL</th>
                            <th>KODE TRX</th>
                            <th>USER / DEPT</th>
                            <th>TOTAL BARANG</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groupedTransactions as $code => $group)
                        @php
                        $firstItem = $group->first();
                        $isPending = $group->contains('status', 'pending');
                        $totalQty = $group->sum('qty');
                        // ID Unik untuk modal
                        $modalID = md5($code);
                        @endphp

                        <tr>
                            <td style="vertical-align: middle;">{{ $firstItem->created_at->format('d M Y') }}</td>
                            <td style="font-family:'Roboto Mono'; font-weight:bold; vertical-align: middle;">
                                {{ $code }}
                            </td>
                            <td style="vertical-align: middle;">
                                <b>{{ $firstItem->user->name }}</b><br>
                                <small class="text-muted">{{ $firstItem->user->department }}</small>
                            </td>
                            <td style="vertical-align: middle;">
                                <span class="badge" style="background:#555; border-radius:0;">{{ $group->count() }} Jenis</span>
                                <small class="text-muted">(Total {{ $totalQty }} pcs)</small>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                @if($isPending)
                                <span class="label label-warning" style="border-radius:0;">MENUNGGU</span>
                                @else
                                <span class="label label-success" style="border-radius:0;">SELESAI</span>
                                @endif
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <button class="btn btn-primary btn-sm btn-industrial" data-toggle="modal" data-target="#modal-{{ $modalID }}">
                                    <i class="glyphicon glyphicon-list-alt"></i> LIHAT DETAIL
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada permintaan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

@foreach($groupedTransactions as $code => $group)
@php
$firstItem = $group->first();
$modalID = md5($code);
@endphp

<div id="modal-{{ $modalID }}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 0; border: 3px solid #333;">

            <div class="modal-header" style="background: #222; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                <h4 class="modal-title" style="font-family: 'Roboto Mono'">
                    DETAIL: {{ $code }} <br>
                    <small style="color: #ccc;">User: {{ $firstItem->user->name }} ({{ $firstItem->user->department }})</small>
                </h4>
            </div>

            <div class="modal-body" style="padding: 0;">
                <table class="table table-striped table-bordered" style="margin-bottom: 0;">
                    <thead style="background: #eee;">
                        <tr>
                            <th>NAMA BARANG</th>
                            <th class="text-center">QTY MINTA</th>
                            <th class="text-center">STOK GUDANG</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group as $trx)
                        <tr>
                            <td>{{ $trx->item ? $trx->item->name : 'Item Dihapus' }}</td>
                            <td class="text-center font-bold" style="font-size: 16px;">{{ $trx->qty }}</td>
                            <td class="text-center">{{ $trx->item ? $trx->item->stock : 0 }}</td>
                            <td class="text-center">
                                @if($trx->status == 'pending')
                                <span class="label label-warning">PENDING</span>
                                @elseif($trx->status == 'approved')
                                <span class="label label-success">OK</span>
                                @else
                                <span class="label label-danger">DITOLAK</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($trx->status == 'pending')
                                <form action="{{ route('admin.trx.approve', $trx->id) }}" method="POST" style="display:inline;">
                                    {{ csrf_field() }}
                                    <button class="btn btn-success btn-xs btn-industrial" title="Setujui Barang Ini">
                                        <i class="glyphicon glyphicon-ok"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.trx.reject', $trx->id) }}" method="POST" style="display:inline;">
                                    {{ csrf_field() }}
                                    <button class="btn btn-danger btn-xs btn-industrial" title="Tolak Barang Ini">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </button>
                                </form>
                                @else
                                <small class="text-muted">- Selesai -</small>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 15px; background: #fafafa; border-top: 1px solid #ddd;">
                    <b>Alasan/Catatan User:</b>
                    <p>{{ $firstItem->reason ?? '-' }}</p>
                </div>
            </div>

            <div class="modal-footer" style="display: flex; justify-content: space-between;">

                <div>
                    @if($group->contains('status', 'pending'))
                    <form id="form-approve-all-{{ $modalID }}" action="{{ route('admin.trx.approveAll', $code) }}" method="POST">
                        {{ csrf_field() }}

                        <button type="button"
                            onclick="showConfirmApprove('form-approve-all-{{ $modalID }}', '{{ $code }}')"
                            class="btn btn-success btn-industrial">
                            <i class="glyphicon glyphicon-check"></i> SETUJUI SEMUA ({{ $code }})
                        </button>
                    </form>
                    @endif
                </div>

                <button type="button" class="btn btn-default btn-industrial" data-dismiss="modal">TUTUP</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<div id="modalConfirm" class="modal fade" role="dialog" style="z-index: 1600;">
    <div class="modal-dialog modal-sm" style="margin-top: 15%;">
        <div class="modal-content" style="border-radius: 0; border: 4px solid #f39c12; box-shadow: 0px 0px 20px rgba(0,0,0,0.5);">

            <div class="modal-header" style="background: #222; color: #f39c12; border-bottom: 2px solid #f39c12;">
                <h4 class="modal-title" style="font-family: 'Roboto Mono'; font-weight: bold;">
                    <i class="glyphicon glyphicon-alert"></i> KONFIRMASI
                </h4>
            </div>

            <div class="modal-body" style="background: #fff; color: #333;">
                <p style="font-size: 16px;">Yakin setujui <b>SEMUA</b> barang untuk:</p>
                <h4 id="confirmCodeDisplay" style="font-family: 'Roboto Mono'; font-weight: bold; background: #eee; padding: 5px; text-align: center;">-</h4>
                <p class="text-danger" style="margin-top: 10px;"><small>*Stok akan berkurang otomatis.</small></p>
            </div>

            <div class="modal-footer" style="background: #222; border-top: 2px solid #f39c12;">
                <button type="button" class="btn btn-default btn-industrial pull-left" data-dismiss="modal" style="color: #fff; border-color: #fff; background: transparent;">BATAL</button>
                <button type="button" class="btn btn-warning btn-industrial" id="btnExecuteApprove" style="background: #f39c12; color: #000;">YA, EKSEKUSI</button>
            </div>

        </div>
    </div>
</div>

<script>
    var targetFormID = "";

    function showConfirmApprove(formID, codeTrx) {
        // 1. Simpan ID form yang mau di-submit
        targetFormID = formID;

        // 2. Tampilkan Kode Transaksi di Modal biar jelas
        document.getElementById('confirmCodeDisplay').innerText = codeTrx;

        // 3. Munculkan Modal Konfirmasi
        $('#modalConfirm').modal('show');
    }

    // Saat tombol "YA, EKSEKUSI" diklik
    document.getElementById('btnExecuteApprove').addEventListener('click', function() {
        if (targetFormID) {
            document.getElementById(targetFormID).submit();
        }
    });
</script>

@endsection