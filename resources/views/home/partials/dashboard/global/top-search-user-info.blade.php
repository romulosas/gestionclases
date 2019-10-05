@section('top-search-user-info')
    <ul class="nav pull-right top-menu">
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <img alt="" src="{{ asset('theme/img/avatar1_small.jpg') }}">
                <span class="username">Luciene Barbosa</span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout dropdown-menu-right">
                <div class="log-arrow-up"></div>
                <li><a href="#"><i class=" fa fa-suitcase"></i>Perfil</a></li>
                <li><a href="#"><i class="fa fa-cog"></i> Configuraciones</a></li>
                <li><a href="#"><i class="fa fa-bell-o"></i> Notificaciones</a></li>
                <li><a href="javascript:void(0);" class="logout"><i class="fa fa-key"></i> Cerrar Sesión</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
    </ul>
@endsection
