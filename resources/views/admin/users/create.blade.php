@extends('layouts.admin')
@section('title', 'Tambah User')
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-plus"></i> INPUT DATA KARYAWAN
            </div>
            <div class="panel-body" style="padding: 30px;">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required placeholder="Nama karyawan...">
                    </div>
                    <div class="form-group">
                        <label>Email (Untuk Login)</label>
                        <input type="email" name="email" class="form-control" required placeholder="email@pabrik.com">
                    </div>
                    <div class="form-group">
                        <label>Password Awal</label>
                        <input type="text" name="password" class="form-control" required placeholder="Min 6 karakter">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Departemen</label>
                                <select name="department" class="form-control">
                                    <option value="Assy">Assy</option>
                                    <option value="Mach">Mach</option>
                                    <option value="PPC">PPC</option>
                                    <option value="Finance">Finance</option>
                                    <option value="HRGA">HRGA</option>
                                    <option value="QC">QC</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role / Jabatan</label>
                                <select name="role" class="form-control">
                                    <option value="user">USER (Staff)</option>
                                    <option value="admin">ADMIN (Superuser)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-warning btn-industrial btn-block">SIMPAN DATA</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-industrial btn-block">BATAL</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection