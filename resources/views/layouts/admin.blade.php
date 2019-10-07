<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Administrador Cultura Brasil">
    <meta name="author" content="Romulo Sant´Ana">
    <meta name="keyword" content="Cultura, Brasil, Curso, Idiomas">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('theme/img/favicon.png') }}">

    <title>@section('title', 'Dashboard') - Admin Cultura Brasil</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('theme/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('theme/css/bootstrap-reset.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('theme/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <link href="{{ asset('theme/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ asset('theme/css/owl.carousel.css') }}" type="text/css">

    <!--right slidebar-->
    <link href="{{ asset('theme/css/slidebars.css') }}" rel="stylesheet">

    <!--toastr-->
    <link href="{{ asset('theme/assets/toastr-master/toastr.css') }}" rel="stylesheet" type="text/css" />

    <!-- Custom styles for this template -->
    <link href="{{ asset('theme/css/style.css?v=').rand(0, 9999) }}" rel="stylesheet">
    <link href="{{ asset('theme/css/style-responsive.css') }}" rel="stylesheet" />

    @yield('css')

</head>
<body class="light-sidebar-nav">

<section id="container">
    <!--header start-->
    <header class="header white-bg">

        <div class="sidebar-toggle-box">
            <i class="fa fa-bars"></i>
        </div>

        <!--logo start-->
        <a href="{{ route('home/dashboard') }}" class="logo">Admin<span> </span></a>

        <!--logo end-->
        <div class="nav notify-row" id="top_menu">
            <!--  notification start -->
            @section('top-left-notifications')
                @include('home.partials.dashboard.global.top-left-notifications')
            @show
            <!--  notification end -->
        </div>
        <div class="top-nav ">
            <!--search & user info start-->
            @section('top-search-user-info')
                @include('home.partials.dashboard.global.top-search-user-info')
            @show
            <!--search & user info end-->
        </div>
    </header>
    <!--header end-->

    <!--sidebar start-->
    <aside>
        <div id="sidebar"  class="nav-collapse ">
            <!-- sidebar menu start-->
            @section('sidebar-menu-start')
                @include('home.partials.dashboard.admin.sidebar-menu-start')
            @show
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->

    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <!--custom chart start-->
            @yield('section-content')
            <!--custom chart end-->

        </section>
    </section>
    <!--main content end-->

    @section('footer')
        <!--footer start-->
            <footer class="site-footer">
                <div class="text-center">
                    2019 &copy; Cultura Brasil.
                    <a href="#" class="go-top">
                        <i class="fa fa-angle-up"></i>
                    </a>
                </div>
            </footer>
            <!--footer end-->
    @show


</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ asset('theme/js/jquery.js') }}"></script>
<script src="{{ asset('theme/js/bootstrap.bundle.min.js') }}"></script>
<script class="include" type="text/javascript" src="{{ asset('theme/js/jquery.dcjqaccordion.2.7.js') }}"></script>
<script src="{{ asset('theme/js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ asset('theme/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/js/jquery.sparkline.js') }}" type="text/javascript"></script>
<script src="{{ asset('theme/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>
<script src="{{ asset('theme/js/jquery.customSelect.min.js') }}"></script>
<script src="{{ asset('theme/js/respond.min.js') }}"></script>

<!--right slidebar-->
<script src="{{ asset('theme/js/slidebars.min.js') }}"></script>

<!--common script for all pages-->
<script src="{{ asset('theme/js/common-scripts.js') }}"></script>

<!--script for this page-->
<script src="{{ asset('theme/js/sparkline-chart.js') }}"></script>
<script src="{{ asset('theme/js/easy-pie-chart.js') }}"></script>
<script src="{{ asset('theme/js/count.js') }}"></script>

<!--toastr-->
<script src="{{ asset('theme/assets/toastr-master/toastr.js') }}"></script>

@yield('tags-script')

<script>

    var HOME = '{{ url('/') }}';
    var UID = '{{ Auth::user()->id }}';

    $(document).ready(function() {
        @yield('document-ready')
    });

    //custom select box
    $(function(){
        $('select.styled').customSelect();
    });

</script>

</body>
</html>
