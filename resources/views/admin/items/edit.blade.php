@extends('layouts.admin')

@section('title', 'Edit Barang')

@section('content')
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-pencil"></i> EDIT BARANG: {{ $item->name }}
            </div>
            <div class="panel-body" style="padding: 30px;">
                
                <form action="{{ route('admin.items.update', $item->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }} <div class="form-group">
                        <label>KODE BARANG</label>
                        <input type="text" name="code" class="form-control" value="{{ $item->code }}" required>
                    </div>

                    <div class="form-group">
                        <label>NAMA BARANG</label>
                        <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>KATEGORI</label>
                                <select name="category" class="form-control">
                                    <option value="ATK" {{ $item->category == 'ATK' ? 'selected' : '' }}>ATK (Alat Tulis)</option>
                                    <option value="ELECTRONIC" {{ $item->category == 'ELECTRONIC' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="PAPER" {{ $item->category == 'PAPER' ? 'selected' : '' }}>Kertas</option>
                                    <option value="OTHER" {{ $item->category == 'OTHER' ? 'selected' : '' }}>Lain-lain</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SATUAN (UNIT)</label>
                                <input type="text" name="unit" class="form-control" value="{{ $item->unit }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>STOK SAAT INI</label>
                                <input type="number" name="stock" class="form-control" value="{{ $item->stock }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>SAFETY STOCK (Batas Aman)</label>
                                <input type="number" name="safety_stock" class="form-control" value="{{ $item->safety_stock }}" required>
                                <small class="text-muted">*Notifikasi muncul jika stok di bawah ini.</small>
                            </div>
                        </div>
                    </div>

                    <hr style="border-top: 2px dashed #333;">

                    <div class="text-right">
                        <a href="{{ route('admin.items.index') }}" class="btn btn-default btn-industrial">BATAL</a>
                        <button type="submit" class="btn btn-warning btn-industrial" style="background: #f39c12; color: #000;">
                            SIMPAN PERUBAHAN
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection