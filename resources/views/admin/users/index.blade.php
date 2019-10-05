@extends('v1_default')
@section('masthead-content')
    <div id="masthead">
        <div class="container-fluid">
            <div class="masthead-pad">
                <div class="masthead-text">
                    @include('layouts.title')
                    <h3>Administración de Usuarios <small>Listado de Usuarios</small></h3>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @include('layouts.menu_ad')

    @include('layouts.partials.messages')

    <a href="{{ action('Admin\AdminController@getCreate') }}" class="btn btn-primary btn-medium pull-right" style="margin-right:20px; margin-bottom:20px">Agregar Usuario</a>

    <br>
    
    <div class="tabbable">
        <ul class="nav nav-tabs" role="tablist">
            <li class="active" role="presentation"><a data-toggle="tab" href="#active" role="tab" aria-controls="active">Usuarios Activos  <span class="badge">{{ count($active_users) }}</span></a></li>
            <li role="presentation"><a data-toggle="tab" href="#inactive" role="tab" aria-controls="inactive">Usuarios Inactivos  <span class="badge">{{ count($inactive_users) }}</span></a></li>
        </ul>

        <div class="tab-content">
            <div id="active" class="tab-pane active"  role="tabpanel">
                <!-- <p class="lead">Usuarios Activos</p>
                <hr> -->
                <table class="table" id="active-user-list">
                    <thead>
                    <tr>
                        <th style="text-align: center">Nombre</th>
                        <th style="text-align: center">Usuario</th>
                        <th style="text-align: center">Correo</th>
                        <th style="text-align: center">Roles</th>
                        <th style="text-align: center">Unidades</th>
                        <!-- <th style="text-align: center">Estatus</th> -->
                        <th style="text-align: center" >Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($active_users as $user)
                        <tr>
                            <td style="text-align: left">{{ $user->name }}</td>
                            <td style="text-align: left">{{ $user->username }}</td>
                            <td style="text-align: left">{{ $user->email }}</td>
                            <td style="text-align: left">{{ $user->myRoles() }}</td>
                            <td style="text-align: left">{{ $user->myUnidades() }}</td>
                            <!-- <td style="text-align: center">{{ $user->status ? 'Activo' : 'Inactivo' }}</td> -->
                            <td style="text-align: center">
                                <a href="{{ action('Admin\AdminController@getEdit', $user->id) }}" class="icon-edit btn-large" style="padding: 0px 5px 0px 5px" title="Editar Usuario"></a>
                                <a href="{{ action('Admin\AdminController@getPermissions', $user->id) }}" class="icon-eye-open btn-large" style="padding: 0px 5px 0px 5px" title="Ver Permisos"></a>
                                <a href="{{ action('Admin\AdminController@getDeactivate', $user->id) }}" class="icon-remove btn-large" style="padding: 0px 5px 0px 5px" title="Desactivar Usuario"></a>
                                <a href="{{ action('Admin\AdminController@getReset', $user->id) }}" class="icon-lock btn-large" style="padding: 0px 5px 0px 5px" title="Cambiar Contraseña"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div> <!-- Fin tab users active -->
        
            <div id="inactive" class="tab-pane fade" role="tabpanel">
                <!-- <p class="lead">Usuarios Inactivos</p>
                <hr> -->
                <table class="table" id="inactive-user-list">
                    <thead>
                    <tr>
                        <th style="text-align: center">Nombre</th>
                        <th style="text-align: center">Usuario</th>
                        <th style="text-align: center">Correo</th>
                        <!-- <th style="text-align: center">Estatus</th> -->
                        <th style="text-align: center" >Activar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($inactive_users as $user)
                        <tr>
                            <td style="text-align: left">{{ $user->name }}</td>
                            <td style="text-align: left">{{ $user->username }}</td>
                            <td style="text-align: left">{{ $user->email }}</td>
                            <!-- <td style="text-align: center">{{ $user->status ? 'Activo' : 'Inactivo' }}</td> -->
                            <td style="text-align: center">
                                <a href="{{ action('Admin\AdminController@getActivate', $user->id) }}" class="icon-ok btn-large" style="padding: 0px 5px 0px 5px"></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div> <!-- Fin Users Inative -->
        </div> <!-- Fin tab-content -->
    </div>

@endsection

@section('script')

$(document).ready(function() {
    $('#active-user-list, #inactive-user-list').dataTable({
        language: DATATABLE_LANG, 
		paging: true, 
		"aaSorting": [],        
        "order": [[ 0, "asc" ]]
    });
} );
@endsection