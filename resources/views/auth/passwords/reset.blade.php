@extends('layouts.login')

@section('content')
<form class="form-signin" action="{{ route('password.update') }}" method="POST">
    @csrf
    <h2 class="form-signin-heading">{{ __('Restablecer Contraseña') }} {{ env('APP_SYSTEM_NAME') }}</h2>
    <input type="hidden" name="token" value="{{ $token }}">
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

        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('E-Mail Address') }}" value="{{ old('email') ?? \App\Libraries\Utils::get_user_by_token($token) }}" required autocomplete="email" readonly="readonly">

        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" required autocomplete="new-password" autofocus>
        <input type="password" id="password-confirm" name="password_confirmation" class="form-control" placeholder="{{ __('Confirm Password') }}" required autocomplete="new-password">

        <button class="btn btn-md btn-login btn-block" type="submit">Guardar Nuevo Password</button>
        <hr/>
        <div class="registration">
            <a href="{{ route('login') }}"> Volver a la página de login.</a>
        </div>
    </div>
</form>

@endsection



