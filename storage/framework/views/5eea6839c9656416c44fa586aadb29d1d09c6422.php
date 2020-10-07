<?php $__env->startSection('titulo', 'Avances requerimiento <?php echo e($requerimiento->id); ?>'); ?>
<?php $__env->startSection('contenido'); ?>
	<h1>Avances del requerimiento n° <?php echo e($requerimiento->id); ?></h1>
	<p>
	</p>
	<form method='HEAD' action="/requerimientos/<?php echo e($requerimiento->id); ?>/avances/nuevo">
	<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo Requerimiento</button>
	</form>
	<br>
	<tr>
	<table class="table table-striped">
		<thead>
		    <th scope="col"><strong>Fecha Avance</strong></th>
		    <th scope="col"><strong>Texto Avance</strong></th>
		    <th scope="col"><strong>Editar</strong></th>
		    <th scope="col"><strong>Eliminar</strong></th>
		</thead>
		<tbody>
			<?php $__empty_1 = true; $__currentLoopData = $avances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
				<?php if($avance->idRequerimiento == $requerimiento->id): ?>
				<tr>
						<th scope="row">
							<?php echo e($avance->fechaAvance); ?>

						</th>
						<td>
							<?php echo e($avance->textAvance); ?>

						</td>
						<td>									
							<form method='HEAD' action="/requerimientos/<?php echo e($requerimiento->id); ?>/editar">
								<?php echo e(csrf_field()); ?>

								<button type="submit" value="Editar" class="btn btn-info" name="">Editar</button>
							</form>
						</td>
						<td>									
							<form method='POST' action="/requerimientos/<?php echo e($requerimiento->id); ?>">
								<?php echo e(csrf_field()); ?>

								<?php echo e(method_field('DELETE')); ?>						
								<button type="submit" value="Eliminar" class="btn btn-danger" name="">Eliminar</button>
							</form>
						</td>											
					<?php endif; ?>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
					<p>Error</p>	
				<?php endif; ?>
				</tr>	
		</tbody>		
	</table>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>