<?php $__env->startSection('titulo', "Detalle team"); ?>
<?php $__env->startSection('contenido'); ?>
	<h1>Detalle de Teams:</h1>
	<h2>Team n° <?php echo e($team->id); ?></h2>

	<p>Nombre del team: <strong><?php echo e($team->nameTeam); ?></strong></p>
	<p>Creado el: <strong><?php echo e($team->created_at->format('d-m-Y')); ?></strong></p>

	<p>
		<a href="<?php echo e(url('teams')); ?>">Volver al listado de Teams</a>
    </p>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Teams/show.blade.php ENDPATH**/ ?>