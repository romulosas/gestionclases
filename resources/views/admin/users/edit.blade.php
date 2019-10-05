@extends('v1_default')
@section('masthead')
    <div id="masthead">
        <div class="container-fluid">
            <div class="masthead-pad">
                <div class="masthead-text">
                    @include('layouts.title')
                    <h3>Administración de Usuarios <small>Editar Usuario</small></h3>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @include('layouts.menu_ad')

    @include('layouts.partials.messages')

    <form class="form-horizontal" method="POST" action="{{ action('Admin\AdminController@postEdit') }}" accept-charset="UTF-8">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">
    <!-- Informacion del Programa de Auditoria -->
    <div class="container-fluid">
        <br>

        <div class="span12">
            <div class="control-group ">
                <label for="username" class="control-label">Usuario: </label>
                <div class="controls">
                    <input required="required" class="input-xlarge" type="text" name="username" value="{{ $user->username }}" id="username">
                </div>
                <!-- {{ $errors->first('username', '<p class="text-error">:message</p>')}} -->
            </div>
        </div>
        <div class="span12">
            <div class="control-group ">
                <label for="email" class="control-label">Correo: </label>
                <div class="controls">
                    <input required="required" class="input-xlarge" type="email" name="email" value="{{ $user->email }}" id="email">
                </div>
                <!-- {{ $errors->first('email', '<p class="text-error">:message</p>')}} -->
            </div>
        </div>

        <div class="span12">
            <div class="control-group ">
                <label for="name" class="control-label">Nombre: </label>
                <div class="controls">
                    <input required="required" class="input-xlarge" type="text" name="name" value="{{ $user->name }}" id="name">
                </div>
                <!-- {{ $errors->first('name', '<p class="text-error">:message</p>')}} -->
            </div>
        </div>

        <div class="span12">
            <div class="control-group">
                <label for="department" class="control-label">Departamento:</label>
                <div class="controls">
                    <select class="input-xlarge" id="department" name="department">
                        @foreach($departments as $key => $department)
                            <optgroup label="{{ $key }}">
                                @foreach($department as $key => $d)
                                    <option value="{{ $key }}" {{ $key == $user->department_id ? 'selected' : '' }}>{{ $d }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <!-- {{ $errors->first('department', '<p class="text-error">:message</p>')}} -->
            </div>
        </div>

        <div class="span12">
            <div class="control-group">
                <label for="roles[]" class="control-label">Roles:</label>
                <div class="controls">
                    <select class="input-xlarge select2" id="roles[]" name="roles[]" multiple="multiple">
                        @foreach($roles as $key => $role)
                            <option value="{{ $key }}" {{ in_array($key, $roles_by_user) ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- {{ $errors->first('roles', '<p class="text-error">:message</p>')}} -->
            </div>
        </div>

        <div class="span12">
            <div class="control-group">
                <label for="unidades[]" class="control-label">Unidad:</label>
                <div class="controls">
                    <select class="input-xlarge select2" id="unidades[]" name="unidades[]" multiple="multiple">
                        @foreach($unidades as $key => $unidad)
                            <option value="{{ $key }}" {{ in_array($key, $unidades_by_user) ? 'selected' : '' }}>{{ $unidad }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- {{ $errors->first('unidades', '<p class="text-error">:message</p>')}} -->
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="form-actions tar">
                <input class="btn btn-primary btn-large" type="submit" value="Actualizar">
                <a href="{{ action('Admin\AdminController@getUsers') }}" class="btn btn-default btn-large pull-right" style="margin-right:20px">Cancelar</a>
            </div>
        </div>
    </div>
    </form>
@endsection