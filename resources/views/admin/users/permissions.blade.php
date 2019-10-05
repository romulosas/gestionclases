@extends('v1_default')
@section('masthead')
    <div id="masthead">
        <div class="container-fluid">
            <div class="masthead-pad">
                <div class="masthead-text">
                    @include('layouts.title')
                    <h3>Administración de Usuarios</h3>
                    <h4>Roles de Usuario <small>{{ $user->name }}</small></h4>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @include('layouts.menu_ad')

    @extends('layouts.partials.messages')

    <div class="container">
        <div class="span12">
            <table class="table dataTables" id="permission">
                <thead>
                <tr>
                    <th style="text-align: center">Nombre de Rol</th>
                    <th style="text-align: center">Código</th>
                    <th style="text-align: center" >Eliminar</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td style="text-align: left">{{ $role->rol->name }}</td>
                            <td style="text-align: left">{{ $role->rol->code }}</td>
                            <td style="text-align: center">
                                <a href="{{ action('Admin\AdminController@getRemoveRole', $role->id) }}" class="icon-remove btn-large" title="Eliminar Rol"></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12" style="margin-left: 0">
            <div class="form-actions tar">
                <a href="{{ action('Admin\AdminController@getUsers') }}" class="btn btn-default btn-large pull-right" style="margin-right:20px">Atrás</a>
            </div>
        </div>
    </div>
@endsection