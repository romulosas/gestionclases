@layout('v1_default')
@section('masthead')
    <div id="masthead">
        <div class="container-fluid">
            <div class="masthead-pad">
                <div class="masthead-text">
                    @include('layouts.title')
                    <h3>Administración de Usuarios <small>Asignar Unidades</small></h3>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @include('iso::layouts.menu_ad')

    {{ Form::open('iso/admin/unidades', 'post', array('class'=>'form-horizontal')) }}

    <!-- Informacion del Programa de Auditoria -->
    <div class="container-fluid">
        <br>
        <div class="span12">
            <div class="control-group">
                {{ Form::label('users[]','Usuarios:', array('class'=>'control-label')) }}
                <div class="controls">
                    {{ Form::select('users[]', $users, '', array('multiple'=>'multiple', 'class'=>'input-xlarge cmbSelect2')) }}
                </div>
                {{ $errors->first('users', '<p class="text-error">:message</p>')}}
            </div>
        </div>

        <div class="span12">
            <div class="control-group">
                {{ Form::label('unidades[]','Unidades:', array('class'=>'control-label')) }}
                <div class="controls">
                    {{ Form::select('unidades[]', $unidades, '', array('multiple'=>'multiple', 'class'=>'input-xlarge cmbSelect2')) }}
                </div>
                {{ $errors->first('unidades', '<p class="text-error">:message</p>')}}
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="form-actions tar">
                    {{ Form::submit('Asignar Unidades', array('class' => 'btn btn-primary btn-large')) }}
                    {{ HTML::link('iso/admin/users', 'Atrás', array('class' => 'btn btn-default btn-large pull-right','style'=>'margin-left:10px')); }}
                </div>
            </div>
        </div>
    </div>
    {{Form::close()}}
@endsection