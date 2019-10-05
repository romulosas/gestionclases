@extends('layouts.login')

@section('content')
<form class="form-signin" action="{{ route('password.email') }}" method="POST">
    @csrf
    <h2 class="form-signin-heading">{{ __('Recuperación de Clave') }} {{ env('APP_SYSTEM_NAME') }}</h2>

    <div class="login-wrap">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                <ul>
                    <li>{{ session('status') }}</li>
                </ul>
            </div>
        @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        <br/>

        <button class="btn btn-lg btn-login btn-block" type="submit">Recuperar</button>
            <hr/>
            <div class="registration">
                <a href="{{ route('login') }}"> Volver a la página de login.</a>
            </div>
    </div>
</form>

@endsection



