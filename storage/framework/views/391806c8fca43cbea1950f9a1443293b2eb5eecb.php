<?php $__env->startSection('titulo', 'Modificar contraseña'); ?>

<?php $__env->startSection('contenido'); ?>
<?php if(session()->has('msj')): ?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
    <?php echo e(session('msj')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
<?php endif; ?>
<?php if(session()->has('msj2')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('msj2')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
<?php endif; ?>
<?php if(session()->has('msj3')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('msj3')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
<?php endif; ?>
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-user-circle"></i> Usuarios</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-edit"></i> Cambiar Contraseña</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Cambiar Contraseña del Usuario</div>
                </div>
                <div class="ibox-body"> 
					<form method="POST" action="<?php echo e(url('user/change')); ?>">
						<?php echo e(csrf_field()); ?>

						<div class="form-group row">
							<label for="actual" class="col-md-2 text-md-right">Contraseña actual</label>
							<input class="form-control col-md-4" type="password" name="oldPassword" id="oldPassword">
						</div>
						<div class="form-group row">
							<label for="nueva" class="col-md-2 text-md-right">Nueva contraseña</label>
							<input class="form-control col-md-4" type="password" name="newPassword" id="newPassword">
						</div>
						<div class="form-group row">
							<label for="confirma" class="col-md-2 text-md-right">Confirmar contraseña</label>
							<input class="form-control col-md-4" type="password" name="newPassword2" id="newPassword2">
							<input type="hidden" value="<?php echo e(auth()->user()->id); ?>" name="usuario">
						</div>
						<div class="form-group row">
							<div class="col-md-2"></div>
							<div class="col-md-4 form-inline">
								<div class="col-md-8">
									<button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Modificar Contraseña</button>
								</div>
								<div class="col-md-4">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
        </div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mUsuarios');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Users/cambiar.blade.php ENDPATH**/ ?>