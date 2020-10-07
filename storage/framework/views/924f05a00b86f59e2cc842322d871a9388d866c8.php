<?php $__env->startSection('titulo', "Detalle team"); ?>

<?php $__env->startSection('contenido'); ?>
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-users"></i> Equipos</h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-file-text-o"></i> Detalle de Equipo</li>
	</ol>
</div>
<div class="page-content fade-in-up">
	<div class="row">
		<div class="col-md-12">
			<div class="ibox">
				<div class="ibox-head">
					<div class="ibox-title">Datos del Equipo</div>
				</div>
				<div class="ibox-body">
					<div class="col-md-6">
						<h2>Equipo n° <?php echo e($team->id); ?></h2>
						<br>
						<table class="table table-condensed">
							<tr>
								<td width="35%"><strong>Nombre del equipo</strong></td>
								<td width="65%"><?php echo e($team->nameTeam); ?></td>
							</tr>
							<tr>
								<td><strong>Creado el</strong></td>
								<td><?php echo e($team->created_at->format('d-m-Y')); ?></td>
							</tr>
						</table>
						<br>
						<p><a href="<?php echo e(url('teams')); ?>" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Regresar al listado</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mEquipos');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Teams/show.blade.php ENDPATH**/ ?>