<?php $__env->startSection('contenido'); ?>
	<h1>Detalle de prioridad:</h1>
	<h2>prioridad n° <?php echo e($priority->id); ?></h2>

	<p>Nombre de la prioridad: <strong><?php echo e($priority->namePriority); ?></strong></p>

	<p>
		<a href="<?php echo e(url('priorities')); ?>">Volver al listado de prioridades</a>
    </p>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Priorities/show.blade.php ENDPATH**/ ?>