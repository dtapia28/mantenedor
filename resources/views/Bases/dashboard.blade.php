<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width initial-scale=1.0 shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('titulo') - Kinchika</title>
  
  <link href="{{ asset('vendor/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('vendor/themify-icons/css/themify-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/main.min.css') }}" rel="stylesheet" />
  <?php use \App\Http\Controllers\HomeController; $color = HomeController::colorSitio(); $linkLogo = HomeController::logoEmpresa(); $nomEmpresa = HomeController::nombreEmpresa();?>
  @switch($color)
	  	@case(1)
		  	<link href="{{ asset('css/themes/red.css') }}" rel="stylesheet" />
		  	@break
		@case(2)
		  	<link href="{{ asset('css/themes/blue.css') }}" rel="stylesheet" />
			@break
		@case(3)
			<link href="{{ asset('css/themes/green.css') }}" rel="stylesheet" />
			@break
		@case(4)
			<link href="{{ asset('css/themes/yellow.css') }}" rel="stylesheet" />
			@break
		@default
		  	@break
  @endswitch
  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  @yield('css')
</head>

<body class="fixed-navbar">
	<div class="page-wrapper">
		{{-- CABECERA --}}
        <header class="header">
			<div class="page-brand">
        	<a class="link" href="{{ url('/requerimientos') }}">
				<span class="brand">Kinchika
					<span class="brand-tip"></span>
          		</span>
				<span class="brand-mini">KcK</span>
			</a>
			</div>
			<div class="flexbox flex-1">
				<ul class="nav navbar-toolbar">
					<li>
						<a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
					</li>
				</ul>
				<ul class="nav navbar-toolbar">
					<li class="dropdown dropdown-user">
						<a class="nav-link dropdown-toggle link" data-toggle="dropdown">
							<img src="{{ asset('img/avatar.png') }}" />
							<span></span>
							<div class="admin-info">
								<div class="font-strong">{{ Auth::user()->name }} <i class="fa fa-angle-down m-l-5"></i></div>
								<small>{{ ucfirst($user[0]->nombre) }}</small>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							@if($user[0]->nombre == "administrador")
							<a class="dropdown-item" href="{{ url('user/parametros') }}"><i class="fa fa-cog"></i> Parámetros</a>
							@endif
							<a class="dropdown-item" href="{{ url('user/changepassword') }}"><i class="fa fa-lock"></i> Cambiar contraseña</a>
							<li class="dropdown-divider"></li>
							<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Cerrar Sesión</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
							@csrf
							</form>
						</ul>
					</li>
				</ul>
			</div>
		</header>
		{{-- MENU --}}
		<nav class="page-sidebar" id="sidebar">
			<div id="sidebar-collapse">
				<div class="admin-block d-flex">
					<div>
						@if($linkLogo != "" && $linkLogo != null)
							<img src="{{ asset($linkLogo) }}" width="45px" height="" alt="Logo"/>
						@else
							<i class="fa fa-refresh fa-2x text-white"></i>
						@endif
					</div>
					<div class="admin-info">
						<div class="font-strong"><?= $nomEmpresa ?></div><small><?= date('d.m.Y') ?></small>
					</div>
				</div>
				<ul class="side-menu metismenu">
					@if(in_array($user[0]->nombre, ["supervisor", "administrador", "solicitante", "resolutor"]))
					<li id="mTablero">
						<a class="active" href="{{ url('/dashboard') }}"><i class="sidebar-item-icon fa fa-th-large"></i>
							<span class="nav-label">Tablero</span>
						</a>
					</li>
					@endif
					<li class="heading">PÁGINAS</li>
					<li id="mRequerimientos">
						<a href="{{ url('/requerimientos') }}"><i class="sidebar-item-icon fa fa-address-card"></i>
							<span class="nav-label">Requerimientos</span>
						</a>
					</li>
					@if($user[0]->nombre == "administrador") 
					<li id="mPrioridades">
						<a href="{{ url('/priorities') }}"><i class="sidebar-item-icon fa fa-sort-amount-desc"></i>
							<span class="nav-label">Prioridades</span>
						</a>
					</li>
					<li id="mResolutores">
						<a href="{{ url('/resolutors') }}"><i class="sidebar-item-icon fa fa-address-book"></i>
							<span class="nav-label">Resolutores</span>
						</a>
					</li>
					<li id="mSolicitantes">
						<a href="{{ url('/solicitantes') }}"><i class="sidebar-item-icon fa fa-address-book-o"></i>
							<span class="nav-label">Solicitantes</span>
						</a>
					</li>
					<li id="mEquipos">
						<a href="{{ url('/teams') }}"><i class="sidebar-item-icon fa fa-users"></i>
							<span class="nav-label">Equipos</span>
						</a>
					</li>
					<li id="mUsuarios">
						<a href="{{ url('/users') }}"><i class="sidebar-item-icon fa fa-user-circle"></i>
							<span class="nav-label">Usuarios</span>
						</a>
					</li>
					@endif
					@if($user[0]->nombre == "supervisor" or $user[0]->nombre == "administrador")
					<li id="mExportar">
						<a href="{{ url('/extracciones') }}"><i class="sidebar-item-icon fa fa-table"></i>
							<span class="nav-label">Exportar</span>
						</a>
					</li>
					@endif
				</ul>
			</div>
		</nav>
		{{-- CONTENIDO --}}
		<div class="content-wrapper">
			<div class="page-content fade-in-up">
				@yield('contenido')
			</div>
			<footer class="page-footer">
				<div class="font-13">2020 © <b>Kinchika</b> - Todos los derechos reservados.</div>
				<div class="to-top"><i class="fa fa-angle-double-up"></i></div>
			</footer>
		</div>
  	</div>
  	<div class="sidenav-backdrop backdrop"></div>
  	<div class="preloader-backdrop">
      	<div class="page-preloader">Cargando</>
	</div>
	
	<!-- Bootstrap core JavaScript-->
	{{-- <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script> --}}
	<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
	<script src="{{ asset('vendor/popper.js/dist/umd/popper.min.js') }}"></script>
	<script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
	{{-- <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script> --}}
	<!-- Core plugin JavaScript-->
	<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
	<script src="{{ asset('vendor/metisMenu/dist/metisMenu.min.js') }}"></script>
	<script src="{{ asset('vendor/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

	<!-- Page level plugin JavaScript-->
	<script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
	{{-- <script src="{{ asset('vendor/datatables/jquery.dataTables.js') }}"></script>
	<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.js') }}"></script> --}}

	<!-- Custom scripts for all pages-->
	<script src="{{ asset('js/app.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('js/comunes.js') }}" type="text/javascript"></script>
	{{-- <script src="{{ asset('js/sb-admin.min.js') }}"></script> --}}

	<!-- Demo scripts for this page-->
	{{-- <script src="{{ asset('js/datatables-demo.js') }}"></script> --}}
	{{-- <script src="{{ asset('js/chart-area-demo.js') }}"></script> --}}
	<script type="text/javascript">
		window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove(); 
			});
		}, 5000);
	</script>
	@yield('script')
	@yield('script2')
</body>
</html>