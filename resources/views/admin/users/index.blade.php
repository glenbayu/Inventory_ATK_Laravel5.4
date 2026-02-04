@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h2 style="font-family: 'Roboto Mono'; font-weight: bold; border-bottom: 3px solid #333; padding-bottom: 10px;">
            MANAJEMEN USER (STAFF)
        </h2>
        
        <div class="text-right" style="margin-bottom: 20px;">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg btn-industrial">
                <i class="glyphicon glyphicon-plus"></i> TAMBAH USER BARU
            </a>
        </div>

        <div class="panel panel-industrial">
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead style="background: #222; color: #fff;">
                        <tr>
                            <th>NAMA</th>
                            <th>EMAIL</th>
                            <th>DEPARTEMEN</th>
                            <th class="text-center">ROLE</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td style="vertical-align: middle;"><b>{{ $u->name }}</b></td>
                            <td style="vertical-align: middle;">{{ $u->email }}</td>
                            <td style="vertical-align: middle;">{{ $u->department }}</td>
                            <td class="text-center" style="vertical-align: middle;">
                                @if($u->role == 'admin')
                                    <span class="label label-danger" style="border-radius:0;">ADMIN</span>
                                @else
                                    <span class="label label-info" style="border-radius:0;">USER</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-warning btn-xs btn-industrial">
                                    <i class="glyphicon glyphicon-pencil"></i>
                                </a>
                                
                                <button type="button" class="btn btn-danger btn-xs btn-industrial" 
                                        onclick="showDeleteUser('form-del-{{ $u->id }}', '{{ $u->name }}')">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>

                                <form id="form-del-{{ $u->id }}" action="{{ route('admin.users.destroy', $u->id) }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<div id="modalDeleteUser" class="modal fade" role="dialog" style="z-index: 1600;">
    <div class="modal-dialog modal-sm" style="margin-top: 15%;">
        <div class="modal-content" style="border-radius: 0; border: 4px solid #c0392b; box-shadow: 0px 0px 20px rgba(0,0,0,0.5);">
            <div class="modal-header" style="background: #222; color: #c0392b; border-bottom: 2px solid #c0392b;">
                <h4 class="modal-title" style="font-family: 'Roboto Mono'; font-weight: bold;">HAPUS USER?</h4>
            </div>
            <div class="modal-body" style="background: #fff; text-align: center;">
                <p>Yakin pecat/hapus user ini?</p>
                <h4 id="userNameDel" style="font-weight: bold; background: #ffebeb; padding: 10px; border: 1px dashed #c0392b;">-</h4>
            </div>
            <div class="modal-footer" style="background: #222; border-top: 2px solid #c0392b;">
                <button type="button" class="btn btn-default btn-industrial pull-left" data-dismiss="modal">BATAL</button>
                <button type="button" class="btn btn-danger btn-industrial" id="btnExecDelUser" style="background: #c0392b; color:#fff;">YA, HAPUS</button>
            </div>
        </div>
    </div>
</div>

<script>
    var targetForm = "";
    function showDeleteUser(formId, name) {
        targetForm = formId;
        document.getElementById('userNameDel').innerText = name;
        $('#modalDeleteUser').modal('show');
    }
    document.getElementById('btnExecDelUser').addEventListener('click', function() {
        if(targetForm) document.getElementById(targetForm).submit();
    });
</script>
@endsection