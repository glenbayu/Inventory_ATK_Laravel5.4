@extends('layouts.admin')

@section('title', 'Form Pengajuan Barang')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2"> <div class="panel panel-industrial" style="border-top: 5px solid #f39c12;">
            <div class="panel-heading-industrial" style="background: #fff; color: #333; border-bottom: 2px dashed #333;">
                <h3 style="margin: 0; font-family: 'Roboto Mono'; font-weight: bold;">
                    FORM PERMINTAAN MULTI-ITEM
                </h3>
            </div>
            <div class="panel-body" style="padding: 20px;">
                
                <form action="{{ route('user.request.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label>Keperluan / Alasan (Opsional)</label>
                        <input type="text" name="reason" class="form-control" placeholder="Contoh: Kebutuhan Project Gudang X" style="border-width: 2px;">
                    </div>

                    <hr style="border-top: 2px solid #eee;">

                    <table class="table table-bordered" id="item-table" style="background: #fafafa;">
                        <thead style="background: #333; color: #fff;">
                            <tr>
                                <th width="60%">NAMA BARANG</th>
                                <th width="20%">QTY</th>
                                <th width="10%" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <tr class="item-row">
                                <td>
                                    <select name="item_id[]" class="form-control" required style="border: 2px solid #ccc; border-radius: 0;">
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->code }} - {{ $item->name }} (Sisa: {{ $item->stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="qty[]" class="form-control" min="1" value="1" required style="border: 2px solid #ccc; font-weight: bold; border-radius: 0;">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-industrial remove-row" disabled>
                                        <i class="glyphicon glyphicon-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" id="add-row" class="btn btn-success btn-xs btn-industrial" style="margin-bottom: 20px;">
                        <i class="glyphicon glyphicon-plus"></i> TAMBAH BARANG LAIN
                    </button>

                    <hr style="border-top: 2px dashed #333;">

                    <div class="text-right">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-default btn-lg btn-industrial">BATAL</a>
                        <button type="submit" class="btn btn-warning btn-lg btn-industrial" style="background: #f39c12; color: #000;">
                            AJUKAN SEMUA &rarr;
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen
        var tableBody = document.getElementById('table-body');
        var addBtn = document.getElementById('add-row');

        // Fungsi Tambah Baris
        addBtn.addEventListener('click', function() {
            // Ambil baris pertama sebagai template
            var firstRow = tableBody.rows[0];
            // Clone (Duplikat) baris tersebut
            var newRow = firstRow.cloneNode(true);

            // Reset nilai input di baris baru
            var inputs = newRow.getElementsByTagName('input');
            var selects = newRow.getElementsByTagName('select');
            
            for(var i=0; i<inputs.length; i++) inputs[i].value = "1";
            for(var i=0; i<selects.length; i++) selects[i].value = "";

            // Aktifkan tombol hapus di baris baru
            var deleteBtn = newRow.querySelector('.remove-row');
            deleteBtn.disabled = false;
            deleteBtn.onclick = function() {
                this.closest('tr').remove();
            };

            // Masukkan ke tabel
            tableBody.appendChild(newRow);
        });
    });
</script>
@endsection