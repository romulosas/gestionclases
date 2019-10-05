<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>inblue QMS</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Bluei Gestión - Inpact S.A.">
    <meta name="author" content="Inpact S.A.">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('v1/css/font-google.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('v1/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('v1/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('v1/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('v1/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('v1/bootstrap/css/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('v1/css/custom_login.css') }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row logo-box">
            <div class="col-md-12" align="center">
                <div class="logo-box">
                    <div class="login-logo">
                        <img src="{{ asset('v1/img/Logo_QMS.png') }}" alt="Logo QMS" class="img-responsive">
                    </div>
                    <div class="row">
                        @if (count($errors->all()) > 0 || Session::get('loginError', false) === true)
                            <div class="alert alert-error text-center">
                                <a class="close" data-dismiss="alert">&times;</a>
                                @if (Session::get('loginError', false) === true)
                                    {{ BLang::Line('login.error_login') }}
                                @else
                                    {{ $errors->first() }}
                                @endif
                            </div>
                        @endif
                        @if (Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <h4 id="oh-snap!-you-got-an-error!"><b><i class="ion-checkmark"></i> Éxito!</b></h4>
                                <p style="padding-left:10px;"> {{ Session::get('success') }}</p>
                            </div>
                        @endif
                        <div class="col-md-4 col-md-offset-4">
                            <div class="col-md-8 col-md-offset-2" id="login" align="center">
                                <div class="panel panel-primary" style="margin-bottom:0px !important; border-radius:4px 4px 0px 0px;">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Iniciar sesión</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" align="center">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <div class="col-md-8 col-md-offset-2" id="login" align="center">
                            <form action="{{ route('login.post') }}" method="post" class="form-horizontal">
                            @csrf
                            <div class="panel panel-primary" style="border-color: transparent !important; border-radius: 0px !important;">
                                <div class="panel-body">
                                    <div class="form-group col-md-12">
                                        <i class="fa fa-user"></i>
                                        <label for="username" class="control-label">Nombre de usuario:</label>
                                        <input type="text" name="username" id="username" class="form-control" required value="{{ old('username') }}">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <i class="fa fa-key"></i>
                                        <label for="password" class="control-label">Password:</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>

                                    <div class="col-md-12">
                                        <a href="todoreset"> ¿Olvidaste tu clave? </a>
                                    </div>

                                </div>
                                <div class="panel-footer text-center">
                                    <button type="submit" class="btn btn-primary btn-lg">Ingresar</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
