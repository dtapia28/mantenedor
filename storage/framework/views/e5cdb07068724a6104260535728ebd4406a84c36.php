<?php $__env->startSection('titulo', "Detalle de Requerimientos"); ?>
<?php $__env->startSection('tituloRequerimiento'); ?>
	<h1>Detalle de requerimiento:</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('requerimiento'); ?>
	<br>
	<h2>Requerimiento n° <?php echo e($requerimiento->id); ?></h2>
	<br>
	<p><strong>Texto del requerimiento: </strong><?php echo e($requerimiento->textoRequerimiento); ?></p>
	<p>
		<?php $__empty_1 = true; $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
			<?php if($priority->id == $requerimiento->idPrioridad): ?>
				<strong>Prioridad: </strong><?php echo e($priority->namePriority); ?>

			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
		<?php endif; ?>
    </p>
	<p>
		<strong>Fecha de email: </strong><?php echo e(date('d-m-Y', strtotime($requerimiento->fechaEmail))); ?>

    </p>
	<p>
		<strong>Fecha de solicitud: </strong><?php echo e(date('d-m-Y', strtotime($requerimiento->fechaSolicitud))); ?>

    </p>
	<p>
		<?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
			<?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
				<?php if($resolutor->idTeam == $team->id): ?>
					<?php if($resolutor->id == $requerimiento->idResolutor): ?>
						<strong>Equipo: </strong><?php echo e($team->nameTeam); ?>

					<?php endif; ?>
				<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
		<?php endif; ?>
    </p>           	
	<p>
		<?php $__empty_1 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
			<?php if($resolutor->id == $requerimiento->idResolutor): ?>
				<strong>Resolutor: </strong><?php echo e($resolutor->nombreResolutor); ?>

			<?php endif; ?>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
		<?php endif; ?>
    </p>
    <p><strong>Fecha de cierre: </strong> <?php echo e(date('d-m-Y', strtotime($requerimiento->fechaCierre))); ?></p>
    <p>
    	<?php if($requerimiento->fechaRealCierre != null): ?>
    		<strong>Fecha real cierre: </strong> <?php echo e(date('d-m-Y', strtotime($requerimiento->fechaRealCierre))); ?>

    	<?php else: ?>
    		<strong>Fecha real cierre: </strong>
    	<?php endif; ?>
    </p>
    <p>
    	<?php if($resolutor->fechaRealCierre != ""): ?>
    		<strong>Mes de cierre real: </strong> <?php echo e(date('n', strtotime($requerimiento->fechaRealCierre)).strftime("%e")); ?>

    	<?php else: ?>
    		<strong>Mes de cierre: </strong><?php echo e(date('n', strtotime($requerimiento->fechaCierre))); ?>

    	<?php endif; ?>    	
    </p>		    
    <p><strong>Número de cambios: </strong> <?php echo e($requerimiento->numeroCambios); ?></p>
    <p>
    	<?php if($requerimiento->numeroCambios <=1): ?>
    		<strong>Status de cambio: </strong>V
    	<?php elseif($requerimiento->numeroCambios <=3): ?>
    		<strong>Status de cambio: </strong>A
    	<?php else: ?>
    		<strong>Status de cambio: </strong>R		
    	<?php endif; ?>
    </p>
    <p><strong>Días laborales: </strong> <?php echo e($hastaCierre); ?></p>
    <p><strong>Días transcurridos: </strong> <?php echo e($hastaHoy); ?></p>
    <p><strong>Días restantes: </strong> <?php echo e($restantes); ?></p>
    <p><strong>Días excedidos: </strong> <?php echo e($excedidos); ?></p>
    <p><strong>Avance diario: </strong><?php echo e(number_format(100/$hastaCierre, 2, ',', '.')); ?>%</p>

    	<?php if($fechaCierre<=$hoy): ?>
    		    <p><strong>Avance esperado: </strong>100%</p>
    	<?php else: ?>
    		<p><strong>Avance esperado: </strong><?php echo e((100/$hastaCierre)*$hastaHoy); ?>%</p>
    	<?php endif; ?>		      
    <p><strong>Porcentaje ejecutado: </strong> <?php echo e($requerimiento->porcentajeEjecutado); ?>%</p>

    <br>
<?php $__env->stopSection(); ?> 
<?php $__env->startSection('avances'); ?>
	<header><h1>Avances del requerimiento:</h1></header>
		<form method='HEAD' action="../requerimientos/<?php echo e($requerimiento->id); ?>/avances/nuevo">
			<button type="submit" value="Ingresar avance" class="btn btn-primary" name="">Ingresar avance</button>
		</form>		
		<br>
		<table>
		<thead>
			<tr>
				<th></th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php $__empty_1 = true; $__currentLoopData = $avances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>	
			<tr>
			<?php if($avance->idRequerimiento == $requerimiento->id): ?>
				<th style="padding-right: 12px;">
				<strong><?php echo e($avance->created_at->format('d-m-Y')); ?>: </strong><?php echo e($avance->textAvance); ?>

				</th>
				<td style="padding-right: 12px;">
					<form method='HEAD' action="../requerimientos/<?php echo e($requerimiento->id); ?>/avances/<?php echo e($avance->id); ?>/editar">
						<?php echo e(csrf_field()); ?>

						<input type="image" align="center" src="<?php echo e(asset('img/edit.png')); ?>" width="20" height="20">
					</form>
				</td>
				<td style="padding-right: 8px;">
					<form method='POST' action="../requerimientos/<?php echo e($requerimiento->id); ?>/avances/<?php echo e($avance->id); ?>">
						<?php echo e(csrf_field()); ?>

						<?php echo e(method_field('DELETE')); ?>						
						<input type="image" align="center" src="<?php echo e(asset('img/delete.png')); ?>" width="20" height="20">
					</form>					
				</td>					
			<?php endif; ?>
   		 </p>
		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
		<?php endif; ?> 
		</tbody>
		</table>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('anidado'); ?>
	<header><h2>Requerimientos anidados:</h2></header>
	<br>
	<?php $__empty_1 = true; $__currentLoopData = $requerimientosAnidados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
		<th>
			</strong><a href="../requerimientos/<?php echo e($requerimiento->id); ?>"><strong><?php echo e($requerimiento->id); ?></strong></a> <?php echo e($requerimiento->textoRequerimiento); ?>

		</th>
		<br>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
	<?php endif; ?>	

<?php $__env->stopSection(); ?>   
    <?php $__env->startSection('footerMain'); ?>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.detalles2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Requerimientos/show.blade.php ENDPATH**/ ?>