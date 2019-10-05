<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ env('APP_SYSTEM_NAME') }}">
    <meta name="author" content="{{ env('APP_SYSTEM_AUTHOR') }}">
    <meta name="keyword" content="{{ env('APP_SYSTEM_KEYWORDS') }}">

    <link rel="shortcut icon" href="{{ asset('theme/img/favicon.png') }}">

    <title>{{ env('APP_SYSTEM_NAME') }}</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('theme/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/css/bootstrap-reset.css') }}" rel="stylesheet">

    <!--external css-->
    <link href="{{ asset('theme/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="{{ asset('theme/css/fonts.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/css/style-responsive.css') }}" rel="stylesheet" />
</head>

<body class="login-body">

    <div class="container">
        @yield('content')
    </div>

    <script src="{{ asset('theme/js/jquery.js') }}"></script>
    <script src="{{ asset('theme/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        $(document).ready(function(){});
    </script>

</body>
</html>
