	
	<?php $__env->startSection('titulo', "Requerimientos"); ?>
	<?php $__env->startSection('contenido'); ?>
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	 <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
		<header><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
		<h1>Listado de Requerimientos</h1>	
		</header>
		<main>
			<div class="form-check form-check-inline">
				<form method='HEAD' action="<?php echo e(url('requerimientos/nuevo')); ?>">
					<button type="submit" value="Nuevo Requerimiento" class="btn btn-primary" name="">Nuevo</button>
				</form>
			</div>
			<div class="form-check form-check-inline">
				<form class="navbar-form navbar-left pull-right" method='GET' action="<?php echo e(url('requerimientos/')); ?>">
					<select class="custom-select" name="state">
						<option value="">Escoja una opción</option>
						<option value="0">Inactivo</option>
						<option value="1">Activo</option>			      	
					</select>
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Filtrar</button>
				</form>
			</div>
			<div class="form-check form-check-inline">	
				<?php
					if ($valor == 1)
					    {echo "<h5>Activos:</h5>";}
					else {
					    echo "<h5>Inactivos:</h5>";
					    }
				?>
			</div>			
		<br>
	        <div class="card mb-3">
	          <div class="card-header">
	            <i class="fas fa-table"></i>
	            Requerimientos</div>
	          <div class="card-body">
	            <div class="table-responsive">
	              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"  cellspacing="0">
	                <thead>
	                  <tr>
	                    <th>Id</th>
	                    <th>Requerimiento</th>
	                    <th>Fecha Solicitud</th>
	                    <th>Fecha Cierre</th>
	                    <th>Ejecutado (%)</th>
	                    <th>Resolutor</th>
	                    <th>Team</th>
	                    <th></th>
	                    <th></th>
	                    <th></th>
	                    <th>Anidar</th>
	                    <th></th>
	                  </tr>
	                </thead>
	                <tbody>
						<?php $__empty_1 = true; $__currentLoopData = $requerimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
							<tr>
							<th id="tabla" scope="row">
								<a href="../public/requerimientos/<?php echo e($requerimiento->id); ?>">
									<?php echo e($requerimiento->id); ?>

								</a>						
							</th>
							<td width="350px" style="text-align:left;">	
								<?php echo e($requerimiento->textoRequerimiento); ?>

							</td>				
							<td style="text-align: center;">	
								<?php echo e(date('d-m-Y', strtotime($requerimiento->fechaSolicitud))); ?>

							</td>
							<?php if($requerimiento->fechaRealCierre != ""): ?>
							<td width="100px" style="text-align: center;">	
								<?php echo e(date('d-m-Y', strtotime($requerimiento->fechaRealCierre))); ?>

							</td>
							<?php else: ?>							
							<td width="100px" style="text-align: center;">	
								<?php echo e(date('d-m-Y', strtotime($requerimiento->fechaCierre))); ?>

							</td>
							<?php endif; ?>
							<td style="text-align: center;">
								<?php echo e($requerimiento->porcentajeEjecutado); ?>

							</td>
							<td width="100px" style="text-align: center">								
							<?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
								<?php if($requerimiento->resolutor == $resolutor->id): ?>			
								<?php echo e($resolutor->nombreResolutor); ?>

								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
							<?php endif; ?>	
							</td>
							<?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
								<?php if($requerimiento->resolutor == $resolutor->id): ?>	
									<?php $__empty_3 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
										<?php if($resolutor->idTeam == $team->id): ?>
										<td style="text-align: center">				
										<?php echo e($team->nameTeam); ?>

										<?php endif; ?>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
									<?php endif; ?>
								<?php endif; ?>	
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
							<?php endif; ?>	
							</td>							
							<td>
					<form method='HEAD' action="../public/requerimientos/<?php echo e($requerimiento->id); ?>/terminado">
						<?php echo e(csrf_field()); ?>

						<input type="image" align="center" src="<?php echo e(asset('img/correcta-marca.png')); ?>" width="30" height="30">
					</form>
				</td>																
				<td>									
					<form method='HEAD' action="../public/requerimientos/<?php echo e($requerimiento->id); ?>/editar">
						<?php echo e(csrf_field()); ?>

						<input type="image" align="center" src="<?php echo e(asset('img/edit.png')); ?>" width="30" height="30">
					</form>
				</td>
				<td>									
					<form method='POST' action="../public/requerimientos/<?php echo e($requerimiento->id); ?>">
						<?php echo e(csrf_field()); ?>

						<?php echo e(method_field('DELETE')); ?>						
						<input type="image" align="center" src="<?php echo e(asset('img/delete.png')); ?>" width="30" height="30">
					</form>
				</td>
				<td>
					<form method="POST" action="<?php echo e(url('requerimientos/anidar')); ?>">
						<?php echo e(csrf_field()); ?>						
	               	 	<input type="number" name="anidar" class="form-control">
	               	 	<input type="hidden" name="requerimiento" value=<?php echo e($requerimiento->id); ?>>				
				</td>
				<td>
						<button type="submit" class="btn btn-success">Anidar</button>
					</form>
				</td>																                  
	                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
	                    <?php endif; ?>
	                </tbody>
	              </table>
	            </div>
	          </div>
	        </div>												
		</main>
	<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Requerimientos/index.blade.php ENDPATH**/ ?>