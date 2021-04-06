<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $__env->yieldContent('titulo'); ?>- EasyTask</title>

  <!-- Custom fonts for this template-->
  <link href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet">

  <!-- Page level plugin CSS-->
  <link href="<?php echo e(asset('vendor/datatables/dataTables.bootstrap4.css')); ?>" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?php echo e(asset('css/sb-admin.css')); ?>  " rel="stylesheet">
  <link href="<?php echo e(asset('css/estilos.css')); ?>" rel="stylesheet">  



</head>

<body id="page-top">

  <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

    <a class="navbar-brand mr-1" href="/">EasyTask</a>

    <button  class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
      <i class="fas fa-bars"></i>
    </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav bd-navbar-nav flex-row" style="color:#f5f5f5;">

                <li>
                     <a class="nav-link" href="<?php echo e(url('/requerimientos')); ?>">Requerimientos</a>                 
                </li>
                <?php if($user[0]->nombre == "administrador"): ?>                
                <li>
                     <a class="nav-link" href="<?php echo e(url('/priorities')); ?>">Prioridades</a>                  
                </li>
                <?php endif; ?>
                <?php if($user[0]->nombre == "administrador"): ?>
                <li>
                     <a class="nav-link" href="<?php echo e(url('/resolutors')); ?>">Resolutores</a>                
                </li>
                <?php endif; ?>                                              
                <?php if($user[0]->nombre == "administrador"): ?>
                <li>
                     <a class="nav-link" href="<?php echo e(url('/solicitantes')); ?>">Solicitantes</a>                 
                </li>
                <?php endif; ?>
                <?php if($user[0]->nombre == "administrador"): ?>
                <li>
                     <a class="nav-link" href="<?php echo e(url('/teams')); ?>">Equipos</a>                 
                </li>
                <?php endif; ?>
                <?php if($user[0]->nombre == "administrador"): ?>
                <li>
                     <a class="nav-link" href="<?php echo e(url('/users')); ?>">Usuarios</a>                 
                </li>
                <?php endif; ?>                                                                   
            </ul>
        </div>    


    <!-- Navbar -->
    <ul class="navbar-nav ml-auto">
      <!-- Authentication Links -->
      <?php if(auth()->guard()->guest()): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
      </li>
      <?php if(Route::has('register')): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Registrar')); ?></a>
      </li>
      <?php endif; ?>
      <?php else: ?>
      <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
          onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <?php echo e(__('Logout')); ?>

        </a>

        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
          <?php echo csrf_field(); ?>
        </form>
      </div>
    </li>
    <?php endif; ?>
  </ul>  

  </nav>

  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="sidebar navbar-nav">
      <?php if($user[0]->nombre == "supervisor" or $user[0]->nombre == "administrador"): ?>      
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo e(url('/dashboard')); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Tablero</span>
        </a>
      </li>
      <?php endif; ?>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-fw fa-folder"></i>
          <span>Páginas</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="pagesDropdown">
          <a class="dropdown-item" href="<?php echo e(url('requerimientos')); ?>">Requerimientos</a>
          <a class="dropdown-item" href="<?php echo e(url('priorities')); ?>">Prioridades</a>
          <a class="dropdown-item" href="<?php echo e(url('resolutors')); ?>">Resolutores</a>
          <a class="dropdown-item" href="<?php echo e(url('solicitantes')); ?>">Solicitantes</a>
          <a class="dropdown-item" href="<?php echo e(url('teams')); ?>">Equipos</a>
        </div>
      </li>
      <?php if($user[0]->nombre == "supervisor" or $user[0]->nombre == "administrador"): ?>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo e(url('/extracciones')); ?>">
          <i class="fas fa-fw fa-table"></i>
          <span>Exportar</span></a>
      </li>
      <?php endif; ?>      
    </ul>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <?php echo $__env->yieldContent("encabezado1"); ?>
          </li>
            <?php echo $__env->yieldContent("encabezado2"); ?>
        </ol>
<main role="main" class="container">
  <header>
    <?php echo $__env->yieldContent('tituloRequerimiento'); ?>
  </header>
    <div class="row mt-3">
      <div id="requerimiento">
        <?php echo $__env->yieldContent('requerimiento'); ?> 
      </div>
      <div id="avanceanidado">                                 
      <div id="avances">
        <?php echo $__env->yieldContent('avances'); ?>             
      </div>
      <div id="anidado">
        <?php echo $__env->yieldContent('anidado'); ?>
      </div>
      <div id="tareas">
        <?php echo $__env->yieldContent('tareas'); ?>
      </div>                      
    </div>
    <footer>
      <?php echo $__env->yieldContent('footerMain'); ?>
    </footer>    
</main>
        <!-- Icon Cards-->      

      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="login.html">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo e(asset('vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>

  <!-- Page level plugin JavaScript-->
  <script src="<?php echo e(asset('vendor/chart.js/Chart.min.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/datatables/jquery.dataTables.js')); ?>"></script>
  <script src="<?php echo e(asset('vendor/datatables/dataTables.bootstrap4.js')); ?>"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?php echo e(asset('js/sb-admin.min.js')); ?>"></script>

  <!-- Demo scripts for this page-->
  <script src="<?php echo e(asset('js/datatables-demo.js')); ?>"></script>
  <script src="<?php echo e(asset('js/chart-area-demo.js')); ?>"></script>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Bases/detalles2.blade.php ENDPATH**/ ?>