@extends('layouts.admin')
@section('title', 'Edit User')
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-industrial">
            <div class="panel-heading-industrial">
                <i class="glyphicon glyphicon-pencil"></i> EDIT DATA: {{ $user->name }}
            </div>
            <div class="panel-body" style="padding: 30px;">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    
                    <div class="alert alert-warning" style="border-radius:0; font-size:12px; margin-bottom:10px;">
                        <i class="glyphicon glyphicon-info-sign"></i> <b>Info:</b> Kosongkan password jika tidak ingin menggantinya.
                    </div>
                    
                    <div class="form-group">
                        <label>Password Baru (Opsional)</label>
                        <input type="text" name="password" class="form-control" placeholder="Isi hanya jika ingin reset password...">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Departemen</label>
                                <input type="text" name="department" class="form-control" value="{{ $user->department }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role</label>
                                <select name="role" class="form-control">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>USER</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>ADMIN</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-warning btn-industrial btn-block">UPDATE DATA</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-default btn-industrial btn-block">BATAL</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection