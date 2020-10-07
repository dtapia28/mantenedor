<?php $__env->startSection('css'); ?>
	<link href="<?php echo e(asset('vendor/DataTables/datatables.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('titulo', 'Solicitantes'); ?>

<?php $__env->startSection('contenido'); ?>
<?php if(session()->has('msj')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('msj')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
<?php endif; ?>
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-address-book-o"></i> Solicitantes</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Solicitantes</div>
			<?php if($user[0]->nombre == "administrador"): ?>
				<div class="pull-right"><a class="btn btn-success" href="<?php echo e(url('users/nuevo')); ?>" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Registro</a></div>
			<?php endif; ?>
		</div>
		<div class="ibox-body">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"  cellspacing="0">
					<thead>
						<tr>
							<th scope="col"><strong>Nombre</strong></th>
							<?php if($user[0]->nombre == "administrador"): ?>
							<th scope="col"><strong>Acciones</strong></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
					<?php $__empty_1 = true; $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
					<tr>
						<td scope="row">
							<?php echo e($solicitante->nombreSolicitante); ?>

						</td>
						<?php if($user[0]->nombre == "administrador"): ?>
						<td scope="row" class="form-inline">
							<form method='post' action="<?php echo e(url('solicitantes/'.$solicitante->id)); ?>">
								<?php echo e(csrf_field()); ?>

								<?php echo e(method_field('DELETE')); ?>

								<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
							</form>
						</td>
						<?php endif; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
					</tr>
					<?php endif; ?>
					</tbody>		
				</table>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('vendor/DataTables/datatables.min.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
	menu_activo('mSolicitantes');
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "<?php echo e(asset('vendor/DataTables/lang/spanish.json')); ?>"
			},
			pageLength: 10,
			stateSave: true,
		});
	});
	$(document).ready(function() {
		if (window.innerWidth < 768) {
			$('.btn').addClass('btn-sm');
		}
	});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Solicitantes/index.blade.php ENDPATH**/ ?>