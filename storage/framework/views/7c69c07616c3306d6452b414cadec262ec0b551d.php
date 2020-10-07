<?php $__env->startSection('titulo', "Prioridades"); ?>
<?php $__env->startSection('contenido'); ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <link href="<?php echo e(asset('css/dashboard.css')); ?>" rel="stylesheet">
	<h1>Listado de Prioridades</h1>
	<p>
	</p>
	<form method='HEAD' action="<?php echo e(url('priorities/nueva')); ?>">
	<button type="submit" value="Nueva Prioridad" class="btn btn-primary" name="">Nueva</button>
	</form>
	<br>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Prioridades</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th></th>
                    <th></th>                    
                  </tr>
                </tfoot>
                <tbody>
					<?php $__empty_1 = true; $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<tr>
						<th id="tabla" scope="row">
							<a href="/priorities/<?php echo e($priority->id); ?>">					
								<?php echo e($priority->id); ?>

							</a>						
						</th>
						<td style="text-align:left;">	
							<?php echo e($priority->namePriority); ?>

						</td>																				
						<td>									
							<form method='HEAD' action="../public/priorities/<?php echo e($priority->id); ?>/editar">
								<?php echo e(csrf_field()); ?>

								<input type="image" align="center" src="<?php echo e(asset('img/edit.png')); ?>" width="30" height="30">
							</form>
						</td>
						<td>									
							<form method='POST' action="../public/priorities/<?php echo e($priority->id); ?>">
								<?php echo e(csrf_field()); ?>

								<?php echo e(method_field('DELETE')); ?>						
								<input type="image" align="center" src="<?php echo e(asset('img/delete.png')); ?>" width="30" height="30">
							</form>
						</td>														                  
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
<?php $__env->stopSection(); ?>	
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Priorities/index.blade.php ENDPATH**/ ?>