@extends('v1_default')
@section('masthead')
    <div id="masthead">
        <div class="container-fluid">
            <div class="masthead-pad">
                <div class="masthead-text">
                    @include('layouts.title')
                    <h3>Administración de Usuarios <small>Cambiar Contraseña del Usuario: </small><strong> {{$user->name}} </strong></h3>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @include('layouts.menu_ad')

    @include('layouts.partials.messages')

    <form class="form-horizontal" method="POST" action="{{ action('Admin\AdminController@postReset') }}" accept-charset="UTF-8">
    @csrf
    <input type="hidden" name="user_id" value="{{ $user->id }}">
    <!-- Informacion del Programa de Auditoria -->
    <div class="container-fluid">
        <br>

        <!-- <div class="span12">
            <div class="control-group ">
                {{-- Form::label('current_password','Contraseña Actual: ', array('class'=>'control-label')) --}}
                <div class="controls">
                    {{-- Form::password('current_password', array('required' => 'required', 'class'=>'input-xlarge')) --}}
                </div>
                {{ $errors->first('current_password', '<p class="text-error">:message</p>')}}
            </div>
        </div> -->

        <div class="span12">
            <div class="control-group ">
                <label for="password" class="control-label">Nueva Contraseña: </label>
                <div class="controls">
                    <input required="required" class="input-xlarge" type="password" name="password" id="password" aria-autocomplete="list">
                </div>
                <!-- {{ $errors->first('password', '<p class="text-error">:message</p>')}} -->
            </div>
        </div>

       <div class="span12">
            <div class="control-group ">
                <label for="password_confirmation" class="control-label">Confirme Contraseña: </label>
                <div class="controls">
                    <input required="required" class="input-xlarge" type="password" name="password_confirmation" id="password_confirmation">
                </div>
                <!-- {{ $errors->first('password_confirmation', '<p class="text-error">:message</p>')}} -->
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