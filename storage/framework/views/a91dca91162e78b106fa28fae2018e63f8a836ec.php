<?php $__env->startSection('titulo', 'Empresas'); ?>
<?php $__env->startSection('contenido'); ?>
	<h1>Listado de Empresas</h1>
	<p>
	</p>
	<form method='HEAD' action="<?php echo e(url('empresas/nueva')); ?>">
	<button type="submit" value="Nueva Empresa" class="btn btn-primary" name="">Nueva Empresa</button>
	</form>
	<br>
	<tr>
	<table class="table table-striped stacktable">
		<thead>
		    <th width="50px" scope="col"><strong>ID</strong></th>
		    <th width="30px" scope="col"><strong>Nombre</strong></th>
		    <th width="50px" scope="col"><strong></strong></th>
		    <th width="50px;" scope="col"><strong></strong></th>
		</thead>
		<tbody>
			<?php $__empty_1 = true; $__currentLoopData = $empresas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empresa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
				<tr>
				<th scope="row">
					<?php echo e($empresa->id); ?>

				</th>
				<td>
					<a href="../public/empresas/<?php echo e($empresa->id); ?>">									
						<?php echo e($empresa->nombreEmpresa); ?>

					</a>
				</td>
				<td>									
					<form method='HEAD' action="empresas/<?php echo e($empresa->id); ?>/editar">
						<?php echo e(csrf_field()); ?>

						<input style="text-align: center;" type="image" align="center" src="<?php echo e(asset('img/edit.png')); ?>" width="30" height="30">
					</form>
				</td>
				<td>
					<form method='POST' action="empresas/<?php echo e($empresa->id); ?>">
						<?php echo e(csrf_field()); ?>

						<?php echo e(method_field('DELETE')); ?>						
						<input type="image" align="center" src="<?php echo e(asset('img/delete.png')); ?>" width="30" height="30">
					</form>
				</td>								
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
				<li>No hay empresas registradas</li>	
			<?php endif; ?>
				</tr>
		</tbody>		
	</table>
	<?php echo e($empresas->links()); ?>	
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Empresas/index.blade.php ENDPATH**/ ?>