<!DOCTYPE html>
<html>
<head>
    <title>Laporan Transaksi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; }
        .status-ok { color: green; font-weight: bold; }
        .status-pend { color: orange; font-weight: bold; }
        .status-no { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVENTORY SYSTEM - LAPORAN TRANSAKSI</h2>
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>User / Dept</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $key => $t)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $t->created_at->format('d/m/Y') }}</td>
                <td>{{ $t->transaction_code }}</td>
                <td>{{ $t->user->name }} ({{ $t->user->department }})</td>
                <td>{{ $t->item->name }}</td>
                <td>{{ $t->qty }} {{ $t->item->unit }}</td>
                <td>
                    @if($t->status == 'approved') <span class="status-ok">APPROVED</span>
                    @elseif($t->status == 'pending') <span class="status-pend">PENDING</span>
                    @else <span class="status-no">REJECTED</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>