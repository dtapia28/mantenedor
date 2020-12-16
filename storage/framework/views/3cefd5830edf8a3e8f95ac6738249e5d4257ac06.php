  
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width initial-scale=1.0 shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
  <link rel="icon" href="<?php echo e(asset('img/favicon.png')); ?>">
  <title><?php echo $__env->yieldContent('titulo'); ?> - Kinchika</title>
  
  <link href="<?php echo e(asset('vendor/bootstrap/dist/css/bootstrap.min.css')); ?>" rel="stylesheet" />
  <link href="<?php echo e(asset('vendor/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet" />
  <link href="<?php echo e(asset('vendor/themify-icons/css/themify-icons.css')); ?>" rel="stylesheet" />
  <link href="<?php echo e(asset('css/main.min.css')); ?>" rel="stylesheet" />
  <?php use \App\Http\Controllers\HomeController; $color = HomeController::colorSitio(); $linkLogo = HomeController::logoEmpresa(); $nomEmpresa = HomeController::nombreEmpresa(); $linkFoto = HomeController::fotoPerfil();?>
  <?php switch($color):
	  	case (1): ?>
		  	<link href="<?php echo e(asset('css/themes/red.css')); ?>" rel="stylesheet" />
		  	<?php break; ?>
		<?php case (2): ?>
		  	<link href="<?php echo e(asset('css/themes/blue.css')); ?>" rel="stylesheet" />
			<?php break; ?>
		<?php case (3): ?>
			<link href="<?php echo e(asset('css/themes/green.css')); ?>" rel="stylesheet" />
			<?php break; ?>
		<?php case (4): ?>
			<link href="<?php echo e(asset('css/themes/yellow.css')); ?>" rel="stylesheet" />
			<?php break; ?>
		<?php default: ?>
		  	<?php break; ?>
  <?php endswitch; ?>
  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <?php echo $__env->yieldContent('css'); ?>
</head>

<body class="fixed-navbar">
	<div class="page-wrapper">
		
        <header class="header">
			<div class="page-brand">
        	<a class="link" href="<?php echo e(url('/requerimientos')); ?>">
				<span class="brand" style="margin-top:10px"><img src="<?php echo e(asset('img/logo.png')); ?>" alt="logo">
					<span class="brand-tip"></span>
          		</span>
				<span class="brand-mini"><img src="<?php echo e(asset('img/logo_small.png')); ?>" alt="logo"></span>
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
                                                            <?php if($linkFoto != "" && $linkFoto != null): ?>
                                                                    <img src="<?php echo e(asset($linkFoto)); ?>" max-width="64px" max-height="64px" alt="Foto" class="rounded"/>
                                                            <?php else: ?>
                                                                    <img src="<?php echo e(asset('img/avatar.png')); ?>" />
                                                            <?php endif; ?> 
							<span></span>
							<div class="admin-info">
								<div class="font-strong"><?php echo e(Auth::user()->name); ?> <i class="fa fa-angle-down m-l-5"></i></div>
								<small><?php echo e(ucfirst($user->nombre)); ?></small>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							<?php if($user->nombre == "administrador"): ?>
							<a class="dropdown-item" href="<?php echo e(url('user/parametros')); ?>"><i class="fa fa-cog"></i> Parámetros</a>
							<?php endif; ?>
                                                        <a class="dropdown-item" href="<?php echo e(url('user/account')); ?>"><i class="fa fa-user"></i> Mi cuenta</a>
							<a class="dropdown-item" href="<?php echo e(url('user/changepassword')); ?>"><i class="fa fa-lock"></i> Cambiar contrase&ntildea</a>
							<li class="dropdown-divider"></li>
							<a class="dropdown-item" href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Cerrar Sesión</a>
							<form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
							<?php echo csrf_field(); ?>
							</form>
						</ul>
					</li>
				</ul>
			</div>
		</header>
		
		<nav class="page-sidebar" id="sidebar">
			<div id="sidebar-collapse">
				<div class="admin-block d-flex">
					<div>
						<?php if($linkLogo != "" && $linkLogo != null): ?>
							<img src="<?php echo e(asset($linkLogo)); ?>" width="45px" height="" alt="Logo"/>
						<?php else: ?>
							<i class="fa fa-refresh fa-2x text-white"></i>
						<?php endif; ?>
					</div>
					<div class="admin-info">
						<div class="font-strong"><?= $nomEmpresa ?></div><small><?= date('d.m.Y') ?></small>
					</div>
				</div>
				<ul class="side-menu metismenu">
					<?php if(in_array($user->nombre, ["supervisor", "administrador", "solicitante", "resolutor"])): ?>
					<li id="mTablero">
						<a class="active" href="<?php echo e(route('tablero')); ?>"><i class="sidebar-item-icon fa fa-th"></i>
							<span class="nav-label">Tablero</span>
						</a>
					</li>
					<?php endif; ?>
					<?php if(in_array($user->nombre, ["administrador"])): ?>
					<li id="mIndicadores">
						<a href="<?php echo e(route('indicadores')); ?>"><i class="sidebar-item-icon fa fa-tachometer"></i>
							<span class="nav-label">Indicadores</span>
						</a>
					</li>
					<?php endif; ?>
					<li class="heading">P&#193GINAS</li>
					<li id="mRequerimientos">
						<a href="<?php echo e(url('/requerimientos')); ?>"><i class="sidebar-item-icon fa fa-address-card"></i>
							<span class="nav-label">Requerimientos</span>
						</a>
					</li>
                                        <?php if(in_array($user->nombre, ["supervisor", "resolutor"])): ?>
					<li id="mDate">
						<a href="<?php echo e(url('/for_date')); ?>"><i class="sidebar-item-icon fa fa-sort-amount-desc"></i>
							<span class="nav-label">Por Fechas</span>
						</a>
					</li>
                                        <?php endif; ?>
                                        <?php if(in_array($user->nombre, ["supervisor", "resolutor"])): ?>
					<li id="mPriority">
						<a href="<?php echo e(url('/for_priority')); ?>"><i class="sidebar-item-icon fa fa-sort-amount-desc"></i>
							<span class="nav-label">Por Prioridad</span>
						</a>
					</li>
                                        <?php endif; ?>                                       
					<?php if($user->nombre == "administrador"): ?> 
					<li id="mPrioridades">
						<a href="<?php echo e(url('/priorities')); ?>"><i class="sidebar-item-icon fa fa-sort-amount-desc"></i>
							<span class="nav-label">Prioridades</span>
						</a>
					</li>
					<li id="mResolutores">
						<a href="<?php echo e(url('/resolutors')); ?>"><i class="sidebar-item-icon fa fa-address-book"></i>
							<span class="nav-label">Resolutores</span>
						</a>
					</li>
					<li id="mSolicitantes">
						<a href="<?php echo e(url('/solicitantes')); ?>"><i class="sidebar-item-icon fa fa-address-book-o"></i>
							<span class="nav-label">Solicitantes</span>
						</a>
					</li>
					<li id="mEquipos">
						<a href="<?php echo e(url('/teams')); ?>"><i class="sidebar-item-icon fa fa-users"></i>
							<span class="nav-label">Equipos</span>
						</a>
					</li>
					<li id="mUsuarios">
						<a href="<?php echo e(url('/users')); ?>"><i class="sidebar-item-icon fa fa-user-circle"></i>
							<span class="nav-label">Usuarios</span>
						</a>
					</li>
					<?php endif; ?>
					<?php if($user->nombre == "supervisor" or $user->nombre == "administrador"): ?>
					<li id="mExportar">
						<a href="<?php echo e(url('/extracciones')); ?>"><i class="sidebar-item-icon fa fa-table"></i>
							<span class="nav-label">Exportar</span>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			</div>
		</nav>
		
		<div class="content-wrapper">
			<div class="page-content fade-in-up">
				<?php echo $__env->yieldContent('contenido'); ?>
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
	
	<script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/popper.js/dist/umd/popper.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
	
	<!-- Core plugin JavaScript-->
	<script src="<?php echo e(asset('vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/metisMenu/dist/metisMenu.min.js')); ?>"></script>
	<script src="<?php echo e(asset('vendor/jquery-slimscroll/jquery.slimscroll.min.js')); ?>"></script>

	<!-- Page level plugin JavaScript-->
	<script src="<?php echo e(asset('vendor/chart.js/Chart.min.js')); ?>"></script>
	

	<!-- Custom scripts for all pages-->
	<script src="<?php echo e(asset('js/app.min.js')); ?>" type="text/javascript"></script>
	<script src="<?php echo e(asset('js/comunes.js')); ?>" type="text/javascript"></script>
	

	<!-- Demo scripts for this page-->
	
	
	<script type="text/javascript">
		window.setTimeout(function() {
			$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove(); 
			});
		}, 5000);
	</script>
	<?php echo $__env->yieldContent('script'); ?>
	<?php echo $__env->yieldContent('script2'); ?>
</body>
</html><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Bases/dashboard2.blade.php ENDPATH**/ ?>