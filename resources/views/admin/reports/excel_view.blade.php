<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; font-size: 16px; text-align: center; height: 30px; vertical-align: middle;">
                LAPORAN TRANSAKSI BARANG
            </th>
        </tr>
        <tr>
            <th width="15" style="font-weight: bold; border: 1px solid #000;">TANGGAL</th>
            <th width="25" style="font-weight: bold; border: 1px solid #000;">KODE TRX</th>
            <th width="20" style="font-weight: bold; border: 1px solid #000;">NAMA USER</th>
            <th width="15" style="font-weight: bold; border: 1px solid #000;">DEPARTEMEN</th>
            <th width="20" style="font-weight: bold; border: 1px solid #000;">NAMA BARANG</th>
            <th width="10" style="font-weight: bold; border: 1px solid #000;">QTY</th>
            <th width="15" style="font-weight: bold; border: 1px solid #000;">STATUS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $t)
        <tr>
            <td style="border: 1px solid #000;">{{ $t->created_at->format('d/m/Y') }}</td>
            <td style="border: 1px solid #000;">{{ $t->transaction_code }}</td>
            <td style="border: 1px solid #000;">{{ $t->user->name }}</td>
            <td style="border: 1px solid #000;">{{ $t->user->department }}</td>
            <td style="border: 1px solid #000;">{{ $t->item->name }}</td>
            <td style="border: 1px solid #000;">{{ $t->qty }}</td>
            <td style="border: 1px solid #000;">{{ strtoupper($t->status) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>