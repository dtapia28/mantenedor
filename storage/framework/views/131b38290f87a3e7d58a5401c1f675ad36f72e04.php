<?php $__env->startSection('css'); ?>
	<link href="<?php echo e(asset('vendor/DataTables/datatables.min.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('titulo', 'Usuarios'); ?>

<?php $__env->startSection('contenido'); ?>
<?php if(session()->has('msj')): ?>
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
    <?php echo e(session('msj')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
<?php endif; ?>
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-user-circle"></i> Usuarios</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Listado de Usuarios</div>
			<div class="pull-right"><a class="btn btn-success" href="<?php echo e(url('users/nuevo')); ?>" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Registro</a></div>
		</div>
		<div class="ibox-body">
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover" id="dataTable" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><strong>Usuario</strong></th>
							<th><strong>Rol</strong></th>
							<th><strong>Cambiar Contraseña</strong>
						</tr>
					</thead>
					<tbody>
						<?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $us): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<tr>	
							<td>
								<?php echo e($us->name); ?>

							</td>
							<td>	
								<form method="POST" action="<?php echo e(url('users/modificar')); ?>" class="form-inline">
									<?php echo e(csrf_field()); ?>

									<?php
									echo "<select id='role".$us->id."' class='form-control col-md-7 mb-2 mr-sm-2 mb-sm-0' name='idRole'>";
									?>
										<?php $__currentLoopData = $relaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $relacion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<?php if($us->id == $relacion->user_id): ?>
											<?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($role->id); ?>" <?php if($role->id == $relacion->role_id): ?><?php echo e('selected'); ?><?php endif; ?>>
												<?php echo e($role->nombre); ?>

											</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>    
										<?php endif; ?>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<?php
									echo "<select id='team".$us->id."' class='form-control col-md-8' name='idTeam' style='display: none;'>";
									?>
									</select>
							
									<input type="hidden" value="<?php echo e($us->id); ?>" name="idUsers">	
									<button type="submit" class="btn btn-primary btn-sm" style="cursor:pointer"><i class="fa fa-pencil"></i> Cambiar</button>
								</form>
							</td>	
							<td>
								<form method="POST" action="<?php echo e(url('users/cambiar')); ?>" class="form-inline">
									<?php echo e(csrf_field()); ?>

									<input class="form-control mb-2 mr-sm-2 mb-sm-0" type="password" name="cambiar">
									<input type="hidden" value="<?php echo e($us->id); ?>" name="usuario">
									<button class="btn btn-primary btn-sm" type="submit" style="cursor:pointer"><i class="fa fa-check"></i> Aceptar</button>
								</form>
							</td>								
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						</tr>
						<?php endif; ?>
					</tbody>		
				</table>
			</div>
		</div>
	</div>
</div>
<?php
	foreach ($users as $us) {
		echo "<script type='text/javascript'>\n$(document).ready(function(){\n";

		echo "$('#role".$us->id."').on('change', function(){\nvar combo = document.getElementById('role".$us->id."');\nvar selected = combo.options[combo.selectedIndex].text;\nif(selected == 'resolutor' || selected == 'gestor'){\ndocument.getElementById('team".$us->id."').style.display = 'block';\n$.get('users/script', function(teams){\n$('#team".$us->id."').empty();\n$('#team".$us->id."').append(\"<option value=''>Seleccione un equipo</option>\");\n$.each(teams, function(index, value){\n $('#team".$us->id."').append(\"<option value='\"+index+\"'>\"+value+\"</option>\");\n});\n});\n} else {\ndocument.getElementById('team".$us->id."').style.display = 'none';\n}\n});\n});\n</script>\n";
	}
?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('vendor/DataTables/datatables.min.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
	menu_activo('mUsuarios');
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "<?php echo e(asset('vendor/DataTables/lang/spanish.json')); ?>"
			},
			pageLength: 10,
			stateSave: true,
		});
	});
	$(document).ready(function() {
		if (window.innerWidth < 768) {
			$('.btn').addClass('btn-sm');
		}
	});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Users/index.blade.php ENDPATH**/ ?>