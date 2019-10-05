@extends('layouts.login')

@section('content')
<form class="form-signin" action="{{ route('login') }}" method="post">
    @csrf
    <h2 class="form-signin-heading">{{ env('APP_SYSTEM_NAME') }}</h2>

    <div class="login-wrap">

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="text" name="usuario" class="form-control" placeholder="Usuario" autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <label class="checkbox">
            <input type="checkbox" value="remember-me"> Recordarme
        </label>
        <button class="btn btn-lg btn-login btn-block" type="submit">Ingresar</button>
        <hr/>
        <div class="registration">
            Olvidaste tu clave?
            <a href="{{ route('password.request') }}"> Generar nueva clave.</a>
        </div>
    </div>
</form>

@endsection



