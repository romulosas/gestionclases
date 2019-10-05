@extends('v1_default')
@section('masthead')
    <div id="masthead">
        <div class="container-fluid">
            <div class="masthead-pad">
                <div class="masthead-text">
                    @include('layouts.title')
                    <h3>Administración de Usuarios <small>Asignar Roles</small></h3>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @include('layouts.menu_ad')

    <form class="form-horizontal" method="POST" action="{{ action('Admin\AdminController@postRoles') }}" accept-charset="UTF-8">
    <!-- Informacion del Programa de Auditoria -->
    @csrf
    <div class="container-fluid">
        <br>
        <div class="span12">
            <div class="control-group">
                <label for="users[]" class="control-label">Usuarios:</label>
                <div class="controls">
                    <select multiple="multiple" class="input-xlarge cmbSelect2" id="users[]" name="users[]" tabindex="-1">
                        @foreach($users as $key => $user)
                            <option value="{{ $key }}">{{ $user }}</option>
                        @endforeach
                    </select>
                </div>
                {{ $errors->first('users', '<p class="text-error">:message</p>')}}
            </div>
        </div>

        <div class="span12">
            <div class="control-group">
                <label for="roles[]" class="control-label">Roles:</label>
                <div class="controls">
                    <select multiple="multiple" class="input-xlarge cmbSelect2" id="roles[]" name="roles[]" tabindex="-1">
                        @foreach($roles as $key => $role)
                            <option value="{{ $key }}">{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
                {{ $errors->first('roles', '<p class="text-error">:message</p>')}}
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="form-actions tar">
                    <input class="btn btn-primary btn-large" type="submit" value="Asignar Roles">
                    <a href="{{ action('Admin\AdminController@getUsers') }}" class="btn btn-default btn-large pull-right" style="margin-right:20px">Atrás</a>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection