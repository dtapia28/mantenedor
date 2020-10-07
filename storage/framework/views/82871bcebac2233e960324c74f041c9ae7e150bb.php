<?php $__env->startSection('contenido'); ?>
	<h1>Detalle de empresa:</h1>
	<h2>Empresa n° <?php echo e($empresa->id); ?></h2>

	<p>Nombre de la empresa: <strong><?php echo e($empresa->nombreEmpresa); ?></strong></p>
	<p>Creado el: <strong><?php echo e($empresa->created_at->format('d-m-Y')); ?></strong></p>

	<p>
		<a href="../empresas">Volver al listado de Empresas</a>
    </p>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Empresas/show.blade.php ENDPATH**/ ?>