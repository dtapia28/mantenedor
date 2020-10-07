<?php $__env->startSection('contenido'); ?>
	<h1>Listado de Empresas</h1>
	<div>
		<ul>
			<?php $__empty_1 = true; $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
				<li><?php echo e($empresa->nombreEmpresa); ?></li>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
				<li>No hay empresas registradas</li>	
			<?php endif; ?>	
		</ul>
	</div>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>