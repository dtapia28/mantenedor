<?php $__env->startSection('titulo', 'Resolutores'); ?>
<?php $__env->startSection('contenido'); ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
	<h1>Listado de Resolutores</h1>
	<p>
	</p>
	<form method='HEAD' action="<?php echo e(url('resolutors/nuevo')); ?>">
	<button type="submit" value="Nuevo Resolutor" class="btn btn-primary" name="">Nuevo</button>
	</form>
	<br>
	<tr>
		<div class="card mb-3">
	    	<div class="card-header">
	        	<i class="fas fa-table"></i>
	            Resolutores</div>
	          <div class="card-body">
	            <div class="table-responsive">
	              <table class="table table-bordered table-striped table-hover" id="dataTable"width="100%"  cellspacing="0">
	                <thead>
	                	<tr>
						    <th scope="col"><strong>ID</strong></th>
						    <th scope="col"><strong>Nombre</strong></th>
						    <th scope="col"><strong></strong></th>
						    <th scope="col"><strong></strong></th>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<tr>
							<th scope="row">
							    <a href="../public/resolutors/<?php echo e($resolutor->id); ?>">					    
								<?php echo e($resolutor->id); ?>

								</a>
							</th>
						<td>
						<?php echo e($resolutor->nombreResolutor); ?>

						</td>
						<td>									
						<form method='HEAD' action="../public/resolutors/<?php echo e($resolutor->id); ?>/editar">
							<?php echo e(csrf_field()); ?>						
						<input style="text-align: center;" type="image" align="center" src="<?php echo e(asset('img/edit.png')); ?>" width="30" height="30">
						</form>
						</td>
						<td>
							<form method='post' action="../public/resolutors/<?php echo e($resolutor->id); ?>">
							<?php echo e(csrf_field()); ?>

							<?php echo e(method_field('DELETE')); ?>						
						<input type="image" align="center" src="<?php echo e(asset('img/delete.png')); ?>" width="30" height="30">
						</form>
						</td>								
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>	
			<?php endif; ?>
				</tr>
		</tbody>		
	</table>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Resolutors/index.blade.php ENDPATH**/ ?>