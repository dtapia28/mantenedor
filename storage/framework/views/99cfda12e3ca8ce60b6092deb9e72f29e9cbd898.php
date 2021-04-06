<?php $__env->startSection('contenido'); ?>
	<h1>Detalle de Resolutor:</h1>
	<h2>resolutor n° <?php echo e($resolutor->id); ?></h2>

	<p>Nombre de la prioridad: <strong><?php echo e($resolutor->nombreResolutor); ?></strong></p>
	<p>Creado el: <strong><?php echo e($resolutor->created_at->format('d-m-Y')); ?></strong></p>	

	<p>
		<a href="<?php echo e(url('resolutors')); ?>">Volver al listado de Resolutores</a>
    </p>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Resolutors/show.blade.php ENDPATH**/ ?>