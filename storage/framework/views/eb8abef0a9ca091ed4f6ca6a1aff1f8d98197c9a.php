<?php $__env->startSection('titulo', 'Teams'); ?>
<?php $__env->startSection('contenido'); ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
		<h1>Listado de Teams</h1>
		<p>
		</p>
		<form method='HEAD' action="<?php echo e(url('teams/nuevo')); ?>">
		<button type="submit" value="Nuevo Teams" class="btn btn-primary" name="">Nuevo</button>
		</form>
		<br>
		              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%"  cellspacing="0">
		                <thead>
		                	<tr>
							    <th scope="col"><strong>ID</strong></th>
							    <th scope="col"><strong>Nombre</strong></th>
							    <th scope="col"><strong></strong></th>
							    <th scope="col"><strong></strong></th>
							</tr>
						</thead>
			<tbody>
				<?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
					<tr>
					<th scope="row">
						<a href="../public/teams/<?php echo e($team->id); ?>">						    
						<?php echo e($team->id); ?>

						</a>
					</th>
					<th scope="row">
							<?php echo e($team->nameTeam); ?>

					</th>
					<th scope="row">									
						<form method='HEAD' action="../public/teams/<?php echo e($team->id); ?>/editar">
							<?php echo e(csrf_field()); ?>

							<input style="text-align: center;" type="image" align="center" src="<?php echo e(asset('img/edit.png')); ?>" width="30" height="30">
						</form>
					</th>
					<th scope="row">
						<form method='post' action="../public/teams/<?php echo e($team->id); ?>">
							<?php echo e(csrf_field()); ?>

							<?php echo e(method_field('DELETE')); ?>					
							<input type="image" align="center" src="<?php echo e(asset('img/delete.png')); ?>" width="30" height="30">
						</form>
					</th>								
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>	
				<?php endif; ?>
					</tr>
			</tbody>		
		</table>
	</div>
	</div>
	</div>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Teams/index.blade.php ENDPATH**/ ?>