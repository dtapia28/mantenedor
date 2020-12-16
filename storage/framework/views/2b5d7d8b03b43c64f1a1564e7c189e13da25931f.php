<?php $__env->startSection('css'); ?>
	<link href="<?php echo e(asset('vendor/DataTables/datatables.min.css')); ?>" rel="stylesheet" />
	<style type="text/css">
		.table thead th, .table td, .table th {
			vertical-align: middle;
		}
		.table th {
			text-align: center;
		}
	</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('titulo', "Requerimientos"); ?>

<?php $__env->startSection('contenido'); ?>
<?php if(session()->has('msj')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
	<?php echo e(session('msj')); ?>

	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<?php endif; ?>
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
</div>
<div class="form-check form-check-inline">
	<?php
		$estado = Request()->state;
		$valor = Request()->valorN;
		$solicitantereq = Request()->solicitante;
	?>
	
	<form class="navbar-form navbar-left pull-right" method='GET' action="<?php echo e(url('/for_priority')); ?>">
		<select id="state" class="custom-select" name="tipo">

			<option selected="true" disabled="disabled" value="">Escoja una opcion...</option>
                        <option value="1">Alta</option>
			<option value="2">Media</option>
                        <option value="3">Baja</option>
		</select>
		<button class="btn btn-outline-primary my-2 my-sm-0" type="submit" style="cursor:pointer">Filtrar</button>
	</form>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<?php if($user->nombre == "solicitante" or $user->nombre == "administrador"): ?>
			<div class="pull-right"><a class="btn btn-success" href="<?php echo e(url('requerimientos/nuevo')); ?>" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Requerimiento</a></div>
			<?php endif; ?>                     
		</div>
		<div class="ibox-body">	
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>							
							<th>Id</th>
							<th>Requerimiento/Tarea</th>
							<th>Fecha Solicitud</th>
							<th>Fecha Cierre</th>
							<th>Resolutor</th>
							<th>Avance (%)</th>
							<th>Estatus</th>
							<th>Anidados</th>
							<th>Acciones</th>
							
						</tr>
					</thead>
					<tbody style="font-size:13px">
						<?php $__empty_1 = true; $__currentLoopData = $requerimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<tr>
							<td style="white-space: nowrap;">
                                                            <?php if($requerimiento->tipo == "tarea"): ?>
                                                                <a href="<?php echo e(url("requerimientos/{$requerimiento->idRequerimiento}")); ?>">
                                                            <?php else: ?>
								<a href="<?php echo e(url("requerimientos/{$requerimiento->id}")); ?>">                                                            
                                                            <?php endif; ?>
									<?php echo e($requerimiento->id2); ?>

								</a>					
							</td>
							<td style="font-size: 0.9rem; max-width: 15vw; overflow-wrap: break-word;">	
                                                            <?php if($requerimiento->tipo == "tarea"): ?>
                                                                <?php echo e($requerimiento->titulo_tarea); ?>

                                                            <?php else: ?>
                                                                <?php echo e($requerimiento->textoRequerimiento); ?>

                                                            <?php endif; ?>
							</td>
							<td style="font-size:13px !important">	
								<?php echo e(date('Y-m-d', strtotime($requerimiento->fechaSolicitud))); ?>

							</td>
							<?php if($requerimiento->fechaRealCierre != ""): ?>
							<td style="font-size:13px !important">	
								<?php echo e(date('Y-m-d', strtotime($requerimiento->fechaRealCierre))); ?>

							</td>
							<?php else: ?>
							<td style="font-size:13px !important">	
								<?php echo e(date('Y-m-d', strtotime($requerimiento->fechaCierre))); ?>

							</td>
							<?php endif; ?>
							<td>								
								<?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
								<?php if($requerimiento->resolutor == $resolutor->id): ?>			
								<?php echo e($resolutor->nombreResolutor); ?>

								<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
								<?php endif; ?>	
							</td>
							<td>
								<?php echo e($requerimiento->porcentajeEjecutado); ?>

							</td>
							<td class="text-center">
								<?php if($requerimiento->status == 1): ?>
								<span class="badge badge-default">Al dia <i class="fa fa-circle text-success"></i></span>
								<?php elseif($requerimiento->status == 2): ?>
								<span class="badge badge-default">Por Vencer <i class="fa fa-circle text-warning"></i></span>
								<?php else: ?>
								<span class="badge badge-default">Vencido <i class="fa fa-circle text-danger"></i></span>
								<?php endif; ?>					
							</td>
							<td class="text-center">
								<?php
								$conteo = 0;
								foreach ($anidados as $anidado) {
									if ($requerimiento->id == $anidado->idRequerimientoBase) {
										$conteo++;
									}
								}
								if ($conteo>0) {
									echo '<span class="badge badge-success">Si</span>';
								} else {
									echo '<span class="badge badge-danger">No</span>';
								}
								?>
							</td>
							<td>
							<div scope="row" class="btn-group">   
							<?php if($user->nombre == "resolutor" or $user->nombre == "administrador"): ?>
                                                            <?php if($requerimiento->tipo != "tarea"): ?>
								<form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/anidar")); ?>">
									<?php echo e(csrf_field()); ?>

									<button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
								</form>
                                                            <?php endif; ?>
							<?php endif; ?>						
                                    <?php if($user->nombre == "resolutor" or $user->nombre == "administrador" or $user->nombre == "supervisor"): ?>
                                    <?php if($user->nombre == "resolutor" and $res->id == $requerimiento->resolutor): ?>
                                    &nbsp;
                                        <?php if($requerimiento->tipo == "tarea"): ?>
                                            <form method='GET' action="<?php echo e(url("/requerimientos/{$requerimiento->idRequerimiento}/tareas/{$requerimiento->id}/terminar")); ?>">					
                                                <?php echo e(csrf_field()); ?>

                                                <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="tarea" value=<?php echo e($requerimiento->id); ?>>
                                                <input type="hidden" name="req" value="<?php echo e($requerimiento->idRequerimiento); ?>">
                                            </form>                                        
                                        <?php else: ?>
                                        <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/terminado")); ?>">
                                            <?php echo e(csrf_field()); ?>

                                            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                        </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if($user->nombre == "supervisor" and $res->id == $requerimiento->resolutor): ?>
                                    &nbsp;
                                        <?php if($requerimiento->tipo == "tarea"): ?>
                                            <form method='GET' action="<?php echo e(url("/requerimientos/{$requerimiento->idRequerimiento}/tareas/{$requerimiento->id}/terminar")); ?>">					
                                                <?php echo e(csrf_field()); ?>

                                                <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="tarea" value=<?php echo e($requerimiento->id); ?>>
                                                <input type="hidden" name="req" value="<?php echo e($requerimiento->idRequerimiento); ?>">
                                            </form>                                        
                                        <?php else: ?>
                                        <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/terminado")); ?>">
                                            <?php echo e(csrf_field()); ?>

                                            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                        </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if( $user->nombre == "administrador"): ?>
                                    &nbsp;
                                        <?php if($requerimiento->tipo == "tarea"): ?>
                                            <form method='GET' action="<?php echo e(url("/requerimientos/{$requerimiento->idRequerimiento}/tareas/{$requerimiento->id}/terminar")); ?>">					
                                                <?php echo e(csrf_field()); ?>

                                                <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                                <input type="hidden" name="tarea" value=<?php echo e($requerimiento->id); ?>>
                                                <input type="hidden" name="req" value="<?php echo e($requerimiento->idRequerimiento); ?>">
                                            </form>                                        
                                        <?php else: ?>
                                        <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/terminado")); ?>">
                                            <?php echo e(csrf_field()); ?>

                                            <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                        </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php endif; ?> 
							<?php if($user->nombre == "solicitante" or $user->nombre == "administrador"): ?>
								&nbsp;
                                                                <?php if($requerimiento->tipo == "tarea"): ?>
								<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->idRequerimiento.'/tareas/'.$requerimiento->id.'/editar')); ?>">
                                                                    <?php echo e(csrf_field()); ?>

                                                                    <button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>                                                                
                                                                <?php else: ?>
								<form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/editar")); ?>">
									<?php echo e(csrf_field()); ?>

									<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>
                                                                <?php endif; ?>
							<?php endif; ?>
                                                        <?php if($user->nombre == "resolutor" and $lider == 1): ?>
                                                        &nbsp;
                                                                <?php if($requerimiento->tipo == "tarea"): ?>
								<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->idRequerimiento.'/tareas/'.$requerimiento->id.'/editar')); ?>">
                                                                    <?php echo e(csrf_field()); ?>

                                                                    <button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>                                                                
                                                                <?php else: ?>
								<form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/editar")); ?>">
									<?php echo e(csrf_field()); ?>

									<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
								</form>
                                                                <?php endif; ?>
                                                        <?php endif; ?>                                            
							<?php if($user->nombre == "solicitante" or $user->nombre == "administrador"): ?>
								&nbsp;
                                                                <?php if($requerimiento->tipo == "tarea"): ?>
								<form method='POST' action="<?php echo e(url('/requerimientos/'.$requerimiento->idRequerimiento.'/tareas/'.$requerimiento->id.'/eliminar')); ?>">
                                                                    <?php echo e(csrf_field()); ?>

                                                                    <?php echo e(method_field('DELETE')); ?>						
                                                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
                                                                    <input type="hidden" name="tarea" value=<?php echo e($requerimiento->id); ?>>
                                                                    <input type="hidden" name="req" value=<?php echo e($requerimiento->idRequerimiento); ?>>
								</form                                                                
                                                                <?php else: ?>
								<form method='POST' action="<?php echo e(url('/requerimientos/'.$requerimiento->id)); ?>">
									<?php echo e(csrf_field()); ?>

									<?php echo e(method_field('DELETE')); ?>						
									<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
								</form>
                                                                <?php endif; ?>
                                                        <?php endif; ?>
							</div>
							</td>
						</tr>										               	
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('vendor/DataTables/datatables.min.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
	$(document).ready(function() {
		let estado = $('#state').val();
		if (estado != "" && estado != null) {
			storeCacheFilter();
		} else {
			sessionStorage.clear();
		}
		$('#state').on('change', function() {
			var role = $(this).val();
			if(role == "2" || role == "3") {
				document.getElementById("valor").style.display = "block";
				document.getElementById("solicitante").style.display = "none";
				$('#valor').prop('required', true);
				$('#solicitante').val("");
			} else if (role == "5"){
				document.getElementById("solicitante").style.display = "block";
				document.getElementById("valor").style.display = "none";
				$('#valor').val("");
				$('#valor').prop('required', false);
			} else {
				document.getElementById("valor").style.display = "none";
				document.getElementById("solicitante").style.display = "none";
				$('#valor').prop('required', false);
				$('#valor').val("");
				$('#solicitante').val("");
			}
		});
		menu_activo('mPriority');
		if (window.innerWidth < 768) {
        	$('.btn').addClass('btn-sm');
	    }
	});
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "<?php echo e(asset('vendor/DataTables/lang/spanish.json')); ?>"
			},
			pageLength: 10,
			stateSave: true,
		});
	});
	function confirmar(){
		var respuesta = confirm("�Est�s seguro/a que desea activar el requerimiento?");
		if (respuesta == true) {
			return true;
		} else {
			return false;
		}
	}
	function storeCacheFilter() {
		if (typeof(Storage) !== 'undefined') {
            sessionStorage.setItem('stState', $('#state').val());
            sessionStorage.setItem('stValor', $('#valor').val());
			sessionStorage.setItem('stSolicitante', $('#solicitante').val());
			sessionStorage.setItem('stFiltroActivo', 1);
		}
	}
</script>		
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Requerimientos/index3.blade.php ENDPATH**/ ?>