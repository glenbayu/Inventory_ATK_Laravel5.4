@extends('layouts.admin')

@section('title', 'Master Data Barang')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 style="font-family: 'Roboto Mono'; font-weight: bold; border-bottom: 3px solid #333; padding-bottom: 10px;">
            MASTER DATA BARANG
            <button class="btn btn-primary btn-industrial pull-right" data-toggle="modal" data-target="#modalAdd">
                + TAMBAH BARANG BARU
            </button>
        </h2>

        <div class="panel panel-industrial">
            <div class="panel-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr style="background: #eee;">
                            <th>KODE</th>
                            <th>NAMA BARANG</th>
                            <th>KATEGORI</th>
                            <th class="text-center">STOK</th>
                            <th class="text-center">SATUAN</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td style="font-family:'Roboto Mono'">{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category }}</td>
                            <td class="text-center">{{ $item->stock }}</td>
                            <td class="text-center">{{ $item->unit }}</td>
                            <td class="text-center" style="vertical-align: middle;">
                                
                                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-warning btn-xs btn-industrial" style="margin-right: 5px;">
                                    <i class="glyphicon glyphicon-pencil"></i> EDIT
                                </a>

                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.items.destroy', $item->id) }}" method="POST" style="display:inline;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="button" class="btn btn-danger btn-xs btn-industrial" onclick="showDeleteModal('delete-form-{{ $item->id }}', '{{ $item->name }}')">
                                        <i class="glyphicon glyphicon-trash"></i> HAPUS
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>

<div id="modalAdd" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#222; color:#fff">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff">&times;</button>
                <h4 class="modal-title">TAMBAH BARANG BARU</h4>
            </div>
            <form action="{{ route('admin.items.store') }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Barang (Barcode)</label>
                        <input type="text" name="code" class="form-control" placeholder="CTH: ATK-001" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Pulpen Standard" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category" class="form-control">
                            <option value="ATK">ATK</option>
                            <option value="Kertas">Kertas</option>
                            <option value="Electronics">Electronics</option>

                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Stok Awal</label>
                            <input type="number" name="stock" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label>Satuan</label>
                            <input type="text" name="unit" class="form-control" placeholder="Pcs/Pack/Rim/Lembar">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-industrial">SIMPAN KE DATABASE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalDelete" class="modal fade" role="dialog" style="z-index: 1600;">
    <div class="modal-dialog modal-sm" style="margin-top: 15%;">
        <div class="modal-content" style="border-radius: 0; border: 4px solid #c0392b; box-shadow: 0px 0px 20px rgba(0,0,0,0.5);">

            <div class="modal-header" style="background: #222; color: #c0392b; border-bottom: 2px solid #c0392b;">
                <h4 class="modal-title" style="font-family: 'Roboto Mono'; font-weight: bold;">
                    <i class="glyphicon glyphicon-alert"></i> HAPUS BARANG?
                </h4>
            </div>

            <div class="modal-body" style="background: #fff; color: #333;">
                <p style="font-size: 14px;">Barang ini akan dihapus permanen dari sistem:</p>
                <h4 id="deleteItemName" style="font-family: 'Roboto Mono'; font-weight: bold; background: #ffebeb; padding: 10px; text-align: center; border: 1px dashed #c0392b;">-</h4>
            </div>

            <div class="modal-footer" style="background: #222; border-top: 2px solid #c0392b;">
                <button type="button" class="btn btn-default btn-industrial pull-left" data-dismiss="modal" style="color: #fff; background: transparent; border-color: #fff;">BATAL</button>
                <button type="button" class="btn btn-danger btn-industrial" id="btnExecuteDelete" style="background: #c0392b; border-color: #000;">
                    YA, MUSNAHKAN
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    var formToDelete = "";

    function showDeleteModal(formId, itemName) {
        // 1. Simpan ID form yang mau dihapus
        formToDelete = formId;

        // 2. Tampilkan nama barang di modal biar user yakin
        document.getElementById('deleteItemName').innerText = itemName;

        // 3. Munculkan Modal
        $('#modalDelete').modal('show');
    }

    // Saat tombol "YA, MUSNAHKAN" diklik
    document.getElementById('btnExecuteDelete').addEventListener('click', function() {
        if (formToDelete) {
            document.getElementById(formToDelete).submit();
        }
    });
</script>
@endsection