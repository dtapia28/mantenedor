<?php $__env->startSection('titulo', 'Detalle solicitante'); ?>
<?php $__env->startSection('contenido'); ?>
	<h1>Detalle de solicitante:</h1>
	<h2>Solicitante n° <?php echo e($solicitante->id); ?></h2>

	<p>Nombre del solicitante: <strong><?php echo e($solicitante->nombreSolicitante); ?></strong></p>
	<p>Creado el: <strong><?php echo e($solicitante->created_at->format('d-m-Y')); ?></strong></p>

	<p>
		<a href="<?php echo e(url('solicitantes')); ?>">Volver al listado de Solicitantes</a>
    </p>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Solicitantes/show.blade.php ENDPATH**/ ?>