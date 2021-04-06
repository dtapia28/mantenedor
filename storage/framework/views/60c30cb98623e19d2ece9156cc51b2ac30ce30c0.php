<?php $__env->startSection('titulo', "Anidar"); ?>

<?php $__env->startSection('contenido'); ?>
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-compress"></i> Anidar</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Requerimientos</div>
		</div>
		<div class="ibox-body">	
			<?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador"): ?>
			<?php $__empty_1 = true; $__currentLoopData = $requerimientosAnidados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimientoB): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
				<th>
					<a href="<?php echo e(url('requerimientos/'.$requerimientoB->id)); ?>"><strong><?php echo e($requerimientoB->id2); ?></strong></a> <?php echo e($requerimientoB->textoRequerimiento); ?>

				</th>
				<br>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
			<?php endif; ?>
			<form method="POST" action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/anidar')); ?>">
				<?php echo e(csrf_field()); ?>		
				<div class="table-responsive">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
						<tr>
							<th>Id</th>
							<th>Requerimiento</th>
							<th>Fecha Solicitud</th>
							<th>Fecha Cierre</th>
							<th>Resolutor</th>
							<th>Team</th>
							<th>Anidar</th>
						</tr>
						</thead>
						<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $requerimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimientoA): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<?php if($requerimientoA->id != $requerimiento->id): ?>
							<tr>
							<th id="tabla" scope="row">
								<a href="requerimientos/<?php echo e($requerimientoA->id); ?>">
								<?php echo e($requerimientoA->id2); ?>

								</a>            
							</th>
							<td width="350px" style="text-align:left;"> 
								<?php echo e($requerimientoA->textoRequerimiento); ?>

							</td>       
							<td style="text-align: center;">  
								<?php echo e(date('d-m-Y', strtotime($requerimientoA->fechaSolicitud))); ?>

							</td>
							<?php if($requerimiento->fechaRealCierre != ""): ?>
							<td width="100px" style="text-align: center;">  
								<?php echo e(date('d-m-Y', strtotime($requerimientoA->fechaRealCierre))); ?>

							</td>
							<?php else: ?>             
							<td width="100px" style="text-align: center;">  
								<?php echo e(date('d-m-Y', strtotime($requerimientoA->fechaCierre))); ?>

							</td>
							<?php endif; ?>
							<td width="100px" style="text-align: center">               
							<?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
								<?php if($requerimientoA->resolutor == $resolutor->id): ?>     
								<?php echo e($resolutor->nombreResolutor); ?>

								<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
							<?php endif; ?> 
							</td>
							<?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
								<?php if($requerimientoA->resolutor == $resolutor->id): ?>  
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
							<td style="text-align: center;">
								<input type="checkbox" name="requerimiento<?php echo e($requerimientoA->id); ?>" value="<?php echo e($requerimientoA->id); ?>">
							</td>
						<?php endif; ?>        
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						<?php endif; ?>              
						</tbody>
					</table>
				</div>
				<input type="hidden" value="<?php echo e($requerimiento->id); ?>" name="requerimiento">
				<div class="col-sm-12 form-group">
					<div class="col-md-12 form-inline">
						<div class="col-md-3">
							<a href="<?php echo e(url('requerimientos')); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar al listado</a>
						</div>
						<div class="col-md-6"></div>
						<div class="col-md-3">
							<button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-compress"></i> Anidar Requerimientos</button>
						</div>
					</div>
				</div>
			</form>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Anidar/create.blade.php ENDPATH**/ ?>