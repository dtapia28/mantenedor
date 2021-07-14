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
	
	<form class="navbar-form navbar-left pull-right" method='GET' action="<?php echo e(url('requerimientos/')); ?>">
		<select id="state" class="custom-select" name="state">

			<option selected="true" disabled="disabled" value="">Escoja una opcion...</option>
			<?php if($user[0]->nombre == "gestor" or $user[0]->nombre == "supervisor" or $user[0]->nombre =="administrador"): ?>
			<option value="6" <?php if($estado == "6"): ?> selected <?php endif; ?>>Por autorizar</option>
			<?php endif; ?>
                        <?php if($user[0]->nombre == "solicitante"): ?>
                            <option value="6" <?php if($estado == "6"): ?> selected <?php endif; ?>>Por autorizar</option>		
                        <?php endif; ?>
                        <?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
                            <option value="6" <?php if($estado == "6"): ?> selected <?php endif; ?>>Por autorizar</option>
                        <?php endif; ?>
			<?php if($user[0]->nombre == "resolutor"): ?>
			<option value="7" <?php if($estado == "7"): ?> selected <?php endif; ?>>Esperando autorización</option>
			<?php endif; ?>							
			<option value="0" <?php if($estado == "0"): ?> selected <?php endif; ?>>Inactivo</option>
			<option value="1" <?php if($estado == "1"): ?> selected <?php endif; ?>>Activo</option>
			<option value="2" <?php if($estado == "2"): ?> selected <?php endif; ?>>% mayor o igual que</option>
			<option value="3" <?php if($estado == "3"): ?> selected <?php endif; ?>>% menor o igual que</option>
			<option value="4" <?php if($estado == "4"): ?> selected <?php endif; ?>>Vencidos</option>
			<option value="5" <?php if($estado == "5"): ?> selected <?php endif; ?>>Por solicitante</option>
                        <?php if($user[0]->nombre != "administrador"): ?>
                        <option value="9" <?php if($estado == "9"): ?> selected <?php endif; ?>>Mis solicitudes</option>
                        <?php endif; ?>
                        <?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
                            <option value="10" <?php if($estado == "10"): ?> selected <?php endif; ?>>Mis Requerimientos</option>
                        <?php endif; ?>
                        <?php if($user[0]->nombre == "supervisor"): ?>
                            <option value="10" <?php if($estado == "10"): ?> selected <?php endif; ?>>Mis Requerimientos</option>
                        <?php endif; ?>                         
		</select>
		<input class="form-control col-md-12" type="number" id="valor"  <?php if($valor=="" || $valor==null): ?> style="display: none" <?php endif; ?> name="valorN" placeholder="porcentaje avance" min="1" max="100" <?php if($valor!=""): ?> value="<?php echo e($valor); ?>" <?php endif; ?>>
		<select id='solicitante' class='form-control col-md-12 custom-select' name='solicitante' <?php if($solicitantereq=="" || $solicitantereq==null): ?> style='display: none;' <?php endif; ?>>
			<option selected="true" disabled="disabled" value="">Escoja una opción...</option>
			<?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<option value=<?php echo e($solicitante->id); ?> <?php if($solicitante->id == $solicitantereq): ?> selected <?php endif; ?>><?php echo e($solicitante->nombreSolicitante); ?></option>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>							
		</select>
		<button class="btn btn-outline-primary my-2 my-sm-0" type="submit" style="cursor:pointer">Filtrar</button>
	</form>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">
				Listado de Requerimientos
				<?php if($valor == 1): ?>
				<span class="badge badge-default"><i class="fa fa-circle text-success"></i> Activos</span>
				<?php else: ?>
				<span class="badge badge-default"><i class="fa fa-circle text-danger"></i> Inactivos</span>
				<?php endif; ?>
			</div>
			<?php if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador"): ?>
			<div class="pull-right"><a class="btn btn-success" href="<?php echo e(url('requerimientos/nuevo')); ?>" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Requerimiento</a></div>
			<?php endif; ?>                     
		</div>
		<div class="ibox-body">	
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<?php if($state == 0): ?>
							<th>Activar</th>
							<?php endif; ?>
							<?php if($state == 6): ?>
							<?php if($user[0]->nombre=="gestor" or $user[0]->nombre == "supervisor"): ?>
							<th>Autorizar</th>
							<?php endif; ?>
                                                        <?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
							<th>Autorizar</th>
							<?php endif; ?>                                                        
							<?php endif; ?>							
							<th>Id</th>
							<th>Requerimiento/Tarea</th>
							<th>Fecha Solicitud</th>
							<th>Fecha Cierre</th>
							<th>Resolutor</th>
							<th>Avance (%)</th>
							<?php if($state != 0): ?>
							<th>Estatus</th>
							<?php endif; ?>
							<?php if($state != 6 and $state !=0): ?>
							<th>Anidados</th>
							<?php endif; ?>
							<th>Acciones</th>
							
						</tr>
					</thead>
					<tbody style="font-size:13px">
						<?php $__empty_1 = true; $__currentLoopData = $requerimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
						<tr>
							<?php if($state == 6): ?>
							<?php if($user[0]->nombre=="gestor" or $user[0]->nombre == "supervisor"): ?>
							<td id="tabla" scope="row">
								<form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/autorizar")); ?>">
									<?php echo e(csrf_field()); ?>

									<button onclick="return confirm('�Est�s seguro/a de autorizar el cierre del requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Autorizar</button>
								</form>
							</td>							
							<?php endif; ?>
                                                        <?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
							<td id="tabla" scope="row">
								<form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/autorizar")); ?>">
									<?php echo e(csrf_field()); ?>

									<button onclick="return confirm('�Est�s seguro/a de autorizar el cierre del requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Autorizar</button>
								</form>
							</td>							
							<?php endif; ?>                                                        
							<?php endif; ?>
							<?php if($state == 0): ?>
							<td id="tabla" scope="row">
								<form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/activar")); ?>">
									<?php echo e(csrf_field()); ?>

									<button onclick="return confirm('�Est�s seguro/a de activar el requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Activar</button>
								</form>
							</td>
							<?php endif; ?>
                                                        <?php if($state == 1 or $state == 4): ?>
							<td style="white-space: nowrap;">
                                                            <?php if($requerimiento->tipo == "tarea"): ?>
                                                                <a href="<?php echo e(url("requerimientos/{$requerimiento->idRequerimiento}")); ?>">
                                                            <?php else: ?>
								<a href="<?php echo e(url("requerimientos/{$requerimiento->id}")); ?>">                                                            
                                                            <?php endif; ?>
									<?php echo e($requerimiento->id2); ?>

								</a>					
							</td>
                                                        <?php else: ?>
                                                        <td style="white-space: nowrap;">
                                                            <a href="<?php echo e(url("requerimientos/{$requerimiento->id}")); ?>"><?php echo e($requerimiento->id2); ?></a>
                                                        </td>
                                                        <?php endif; ?>
                                                        <?php if($state == 1 or $state == 4): ?>
							<td style="font-size: 0.9rem; max-width: 15vw; overflow-wrap: break-word;">	
                                                            <?php if($requerimiento->tipo == "tarea"): ?>
                                                                <?php echo e($requerimiento->titulo_tarea); ?>

                                                            <?php else: ?>
                                                                <?php echo e($requerimiento->textoRequerimiento); ?>

                                                            <?php endif; ?>
							</td>
                                                        <?php else: ?>
                                                        <td style="font-size: 0.9rem; max-width: 15vw; overflow-wrap: break-word;">
                                                              <?php echo e($requerimiento->textoRequerimiento); ?>  
                                                        </td>
                                                        <?php endif; ?>
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
							<?php if($state != 0): ?>
							<td class="text-center">
								<?php if($requerimiento->status == 1): ?>
								<span class="badge badge-default">Al dia <i class="fa fa-circle text-success"></i></span>
								<?php elseif($requerimiento->status == 2): ?>
								<span class="badge badge-default">Por Vencer <i class="fa fa-circle text-warning"></i></span>
								<?php else: ?>
								<span class="badge badge-default">Vencido <i class="fa fa-circle text-danger"></i></span>
								<?php endif; ?>					
							</td>
							<?php endif; ?>
							<?php if($state != 6 && $state !=0): ?>
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
							<?php endif; ?>
							<td>
							<div scope="row" class="btn-group">
							<?php if($state != 6 and $state != 7 and $state != 0): ?>    
								<?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador"): ?>
									<?php if($requerimiento->tipo != "tarea"): ?>
									<form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/anidar")); ?>">
										<?php echo e(csrf_field()); ?>

										<button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
									</form>
									<?php endif; ?>
								<?php endif; ?>						
								<?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador" or $user[0]->nombre == "supervisor"): ?>
                                    <?php if($user[0]->nombre == "resolutor" and $res->id == $requerimiento->resolutor): ?>
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
                                    <?php if($user[0]->nombre == "supervisor" and $res->id == $requerimiento->resolutor): ?>
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
                                    <?php if( $user[0]->nombre == "administrador"): ?>
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
							<?php if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador"): ?>
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
							<?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador" or $user[0]->nombre == "supervisor"): ?>
								&nbsp;
								<form method='POST' action="<?php echo e(url("requerimientos/{$requerimiento->id}/adjuntar")); ?>">
									<?php echo e(csrf_field()); ?>

									<button type="submit" class="btn btn-info btn-sm" data-toggle="tooltip" data-original-title="Adjuntar" style="cursor:pointer"><i class="fa fa-paperclip"></i></button>
								</form>
							<?php endif; ?>                                                        
                                                        <?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
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
							<?php if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador"): ?>
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
		menu_activo('mRequerimientos');
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
			<?php if ($estado == "10" && $user[0]->idUser == 25) { ?>
			order: [3, 'asc'],
			<?php } else { ?>			
			stateSave: true,
			<?php } ?>
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
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Requerimientos/index.blade.php ENDPATH**/ ?>