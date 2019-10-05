@extends('v1_default')
@section('masthead')
    <div id="masthead">
        <div class="container-fluid">
            <div class="masthead-pad">
                <div class="masthead-text">
                    @include('layouts.title')
                    <h3>Administración</h3>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')

    @include('layouts.menu_ad')

@endsection