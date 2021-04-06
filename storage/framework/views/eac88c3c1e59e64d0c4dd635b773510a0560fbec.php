<?php $__env->startSection('titulo', "Detalle de Requerimientos"); ?>

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
	<h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><i class="fa fa-file-text-o"></i> Detalle del Requerimiento</li>
	</ol>
</div>
<div class="page-content fade-in-up">
	<div class="row">
		<div class="col-md-12">
			<div class="ibox">
				<?php if($resolutor->idUser != $user[0]->idUser): ?>
				<?php if($lider == 1 and $requerimiento->aprobacion == 4): ?>
				<div class="ibox-head">
					<div class="ibox-title">Datos del Requerimiento</div>
					<div class="pull-right"><a class="btn btn-primary" onclick="return confirm('¿Estás seguro/a de autorizar el cierre del requerimiento?')" href="<?php echo e(url('requerimientos/'.$requerimiento->id.'/aceptar')); ?>" style="white-space: normal;"><i class="fa fa-check"></i> Aceptar</a> <a class="btn btn-outline-danger" href="<?php echo e(url('requerimientos/'.$requerimiento->id.'/rechazar')); ?>" style="white-space: normal;"><i class="fa fa-close"></i> Rechazar</a></div>
				</div>
				<?php endif; ?>
				<?php endif; ?>
				<?php if($user[0]->nombre=="supervisor" and $requerimiento->aprobacion == 4): ?>
				<div class="ibox-head">
					<div class="ibox-title">Datos del Requerimiento</div>
					<div class="pull-right"><a class="btn btn-primary" onclick="return confirm('¿Estás seguro/a de autorizar el cierre del requerimiento?')" href="<?php echo e(url('requerimientos/'.$requerimiento->id.'/aceptar')); ?>" style="white-space: normal;"><i class="fa fa-check"></i> Aceptar</a> <a class="btn btn-outline-danger" href="<?php echo e(url('requerimientos/'.$requerimiento->id.'/rechazar')); ?>" style="white-space: normal;"><i class="fa fa-close"></i> Rechazar</a></div>
				</div>
				<?php endif; ?>				
				<div class="ibox-body">
					<div class="row">
						<div class="col-md-6">
                                                    <?php if($id2 == "INC"): ?>
							<h2>Incidente <?php echo e($requerimiento->id2); ?></h2>
                                                    <?php else: ?>
                                                        <h2>Requerimiento <?php echo e($requerimiento->id2); ?></h2>
                                                    <?php endif; ?>
							<br>
							<table class="table table-condensed">	
								<tr>
									<td width="40%"><strong>Solicitante</strong></td>
									<td width="60%"><?php echo e($solicitante->nombreSolicitante); ?></td>
								</tr>
								<tr>
									<td><strong>Resolutor</strong></td>
									<td><?php echo e($resolutor->nombreResolutor); ?></td>
								</tr>
								<?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador"): ?>
								<tr>
									<td><strong>Comentario</strong></td>
									<td><?php echo e($requerimiento->comentario); ?></td>
								</tr>
								<?php endif; ?>
								<tr>
									<td><strong>Solicitud</strong></td>
									<td><?php echo e($requerimiento->textoRequerimiento); ?></td>
								</tr>
								<?php $__empty_1 = true; $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
								<?php if($priority->id == $requerimiento->idPrioridad): ?>
								<tr>
									<td><strong>Prioridad</strong></td>
									<td><?php echo e($priority->namePriority); ?></td>
								</tr>								
								<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
								<?php endif; ?>
								<tr>
									<td><strong>Fecha original de requerimiento</strong></td>
									<td><?php echo e(date('d-m-Y', strtotime($requerimiento->fechaEmail))); ?></td>
								</tr>
								<tr>
									<td><strong>Fecha de inicio seguimiento</strong></td>
									<td><?php echo e(date('d-m-Y', strtotime($requerimiento->fechaSolicitud))); ?></td>
								</tr>
								<?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
								<?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
								<?php if($resolutor->idTeam == $team->id): ?>
								<?php if($resolutor->id == $requerimiento->idResolutor): ?>
								<tr>
									<td><strong>Equipo</strong></td>
									<td><?php echo e($team->nameTeam); ?></td>
								</tr>
								<?php endif; ?>
								<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
								<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
								<?php endif; ?>
								<?php $__empty_1 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
								<?php if($resolutor->id == $requerimiento->idResolutor): ?>
								<tr>
									<td><strong>Resolutor</strong></td>
									<td><?php echo e($resolutor->nombreResolutor); ?></td>
								</tr>
								<?php endif; ?>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
								<?php endif; ?>
								<tr>
									<td><strong>Fecha solicitada de cierre</strong></td>
									<td><?php echo e(date('d-m-Y', strtotime($requerimiento->fechaCierre))); ?></td>
								</tr>
								<tr>
									<td><strong>Fecha real cierre</strong></td>
									<?php if($requerimiento->fechaRealCierre != null): ?>
									<td><?php echo e(date('d-m-Y', strtotime($requerimiento->fechaRealCierre))); ?></td>
									<?php else: ?>
									<td></td>
									<?php endif; ?>
								</tr>
								<tr>
									<td><strong>Número de cambios</strong></td>
									<td><?php echo e($requerimiento->numeroCambios); ?></td>
								</tr>
								<tr>
									<td><strong>Status de cambio</strong></td>
									<?php if($requerimiento->numeroCambios <=1): ?>
									<td>V</td>
									<?php elseif($requerimiento->numeroCambios <=3): ?>
									<td>A</td>
									<?php else: ?>
									<td>R</td>
									<?php endif; ?>
								</tr>
								<tr>
									<td><strong>Porcentaje ejecutado</strong></td>
									<td><?php echo e($requerimiento->porcentajeEjecutado); ?>%</td>
								</tr>
								<?php if($requerimiento->cierre != ""): ?>
								<tr>
									<td><strong>Cierre</strong></td>
									<td><?php echo e($requerimiento->cierre); ?></td>
								</tr>
								<?php endif; ?>
								</tr>
								<?php if($requerimiento->rechazo != "" and $requerimiento->porcentajeEjecutado != 100): ?>
								<tr>
									<td><strong>Motivo rechazo</strong></td>
									<td><?php echo e($requerimiento->rechazo); ?></td>
								</tr>
								<?php endif; ?>														
							</table>
						</div>
					</div>
					
					<?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "supervisor"): ?>
					<div class="row">
						<div class="col-md-12">
							<h2>Avances</h2>
							<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
							<?php if($res->id == $requerimiento->resolutor): ?>
								<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/avances/nuevo')); ?>">
									<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
								</form>
							<?php endif; ?>
							<?php endif; ?>
							<div class="table-responsive">
								<table class="table table-borderless table-striped table-hover table-sm">
									<thead>
										<tr>
											<th>Fecha Avance</th>
											<th>Texto del Avance</th>
											<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
											<th>Acciones</th>
											<?php endif; ?>
										</tr>		
									</thead>
									<tbody>
									<?php $__empty_1 = true; $__currentLoopData = $avances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>	
									<tr>
										<?php if($avance->idRequerimiento == $requerimiento->id): ?>
										<td>
											<?php echo e($avance->created_at->format('d-m-Y')); ?>

										</td>
										<td><?php echo e($avance->textAvance); ?></td>
										<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
										<td>			
											<div scope="row" class="btn-group">
											<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id.'/editar')); ?>">
												<?php echo e(csrf_field()); ?>

												<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
											</form>
											&nbsp;&nbsp;&nbsp;
											<form method='POST' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id)); ?>">
												<?php echo e(csrf_field()); ?>

												<?php echo e(method_field('DELETE')); ?>

												<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
											</form>	
											</div>					
										</td>
										<?php endif; ?>			
										<?php endif; ?>
									</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
									<?php endif; ?> 
									</tbody>
								</table>
								<?php echo $avances->render(); ?>	
							</div>
						</div>
					</div>
					<?php endif; ?>
					<?php if($user[0]->nombre == "administrador"): ?>
					<div class="row">
						<div class="col-md-12">
							<h2>Avances</h2>
							<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
								<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/avances/nuevo')); ?>">
									<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
								</form>
							<?php endif; ?>
							<div class="table-responsive">
								<table class="table table-borderless table-striped table-hover table-sm">
									<thead>
										<tr>
											<th>Fecha Avance</th>
											<th>Texto del Avance</th>
											<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
											<th>Acciones</th>
											<?php endif; ?>
										</tr>		
									</thead>
									<tbody>
									<?php $__empty_1 = true; $__currentLoopData = $avances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $avance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>	
									<tr>
										<?php if($avance->idRequerimiento == $requerimiento->id): ?>
										<td>
											<?php echo e($avance->created_at->format('d-m-Y')); ?>

										</td>
										<td><?php echo e($avance->textAvance); ?></td>
										<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
										<td>			
											<div scope="row" class="btn-group">
											<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id.'/editar')); ?>">
												<?php echo e(csrf_field()); ?>

												<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
											</form>
											&nbsp;&nbsp;&nbsp;
											<form method='POST' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/avances/'.$avance->id)); ?>">
												<?php echo e(csrf_field()); ?>

												<?php echo e(method_field('DELETE')); ?>

												<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
											</form>	
											</div>					
										</td>
										<?php endif; ?>			
										<?php endif; ?>
									</tr>
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
									<?php endif; ?> 
									</tbody>
								</table>
								<?php echo $avances->render(); ?>	
							</div>
						</div>
					</div>
					<?php endif; ?>					
					
					<?php if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador"): ?>
					<div class="row">
						<div class="col-md-12">
						<h2>Tareas</h2>
						<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
						<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/tareas/nueva')); ?>">
							<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
						</form>
						<?php endif; ?>
						<div class="table-responsive">
							<table class="table table-striped table-hover table-sm">
								<thead>
									<tr>
										<th>N°</th>
										<th>Tarea</th>
										<th>Solicitud</th>
										<th>Cierre</th>
										<th>Resolutor</th>
										<th>Estado</th>
										<th>Acciones</th>
									</tr>		
								</thead>
								<tbody>
									<?php $__empty_1 = true; $__currentLoopData = $tareas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tarea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>	
									<tr>
										<?php if($tarea->estado == 1 or $tarea->estado == 2): ?>
											<td><?php echo e($tarea->id2); ?></td>	
											<td><?php echo e($tarea->textoTarea); ?></td>
											<td><?php echo e(date('d-m-Y', strtotime($tarea->fechaSolicitud))); ?></td>
											<td><?php echo e(date('d-m-Y', strtotime($tarea->fechaCierre))); ?></td>
										<?php endif; ?>
										<?php if($tarea->estado == 1 or $tarea->estado == 2): ?>				
											<td>
											<?php $__empty_2 = true; $__currentLoopData = $resolutores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>	
												<?php echo e($resolutor->nombreResolutor); ?>

											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
											<?php endif; ?>	
											</td>
											<td>			
												<?php if($tarea->estado == 1): ?>
												<span class="badge badge-default">Pendiente <i class="fa fa-circle text-warning"></i></span>
												<?php else: ?>
												<span class="badge badge-default">Completada <i class="fa fa-circle text-success"></i></span>
												<?php endif; ?>	
											</td>
											<td>
											<div scope="row" class="btn-group">
											<?php if($tarea->estado == 1): ?>
												<form method='GET' action="<?php echo e(url("/requerimientos/{$requerimiento->id}/tareas/{$tarea->id}/terminar")); ?>">					
													<?php echo e(csrf_field()); ?>

													<button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
													<input type="hidden" name="tarea" value=<?php echo e($tarea->id); ?>>
													<input type="hidden" name="req" value="<?php echo e($requerimiento->id); ?>">
												</form>
											<?php endif; ?>
											&nbsp;&nbsp;
											<?php if($tarea->estado == 1): ?>									
												<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/editar')); ?>">
													<?php echo e(csrf_field()); ?>

													<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
												</form>
											<?php endif; ?>
											&nbsp;&nbsp;
											<?php if($tarea->estado == 1): ?>							
												<form method='POST' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/eliminar')); ?>">
													<?php echo e(csrf_field()); ?>

													<?php echo e(method_field('DELETE')); ?>						
													<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
													<input type="hidden" name="tarea" value=<?php echo e($tarea->id); ?>>
													<input type="hidden" name="req" value=<?php echo e($requerimiento->id); ?>>
												</form>
											<?php endif; ?>
											</div>
											</td>
										<?php endif; ?>	
									</tr>						
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
									<?php endif; ?> 
								</tbody>	
							</table>
						</div>
					</div>
				</div>
				<?php endif; ?>
					<?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
					<div class="row">
						<div class="col-md-12">
						<h2>Tareas</h2>
						<?php if($requerimiento->estado == 1 and $requerimiento->aprobacion == 3): ?>
						<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/tareas/nueva')); ?>">
							<button type="submit" value="Ingresar" class="btn btn-primary" name="" style="cursor:pointer"><i class="fa fa-plus"></i> Ingresar</button>
						</form>
						<?php endif; ?>
						<div class="table-responsive">
							<table class="table table-striped table-hover table-sm">
								<thead>
									<tr>
										<th>N°</th>
										<th>Tarea</th>
										<th>Solicitud</th>
										<th>Cierre</th>
										<th>Resolutor</th>
										<th>Estado</th>
										<th>Acciones</th>
									</tr>		
								</thead>
								<tbody>
									<?php $__empty_1 = true; $__currentLoopData = $tareas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tarea): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>	
									<tr>
										<?php if($tarea->estado == 1 or $tarea->estado == 2): ?>
											<td><?php echo e($tarea->id2); ?></td>	
											<td><?php echo e($tarea->textoTarea); ?></td>
											<td><?php echo e(date('d-m-Y', strtotime($tarea->fechaSolicitud))); ?></td>
											<td><?php echo e(date('d-m-Y', strtotime($tarea->fechaCierre))); ?></td>
										<?php endif; ?>
										<?php if($tarea->estado == 1 or $tarea->estado == 2): ?>				
											<td>
											<?php $__empty_2 = true; $__currentLoopData = $resolutores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>	
												<?php echo e($resolutor->nombreResolutor); ?>

											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
											<?php endif; ?>	
											</td>
											<td>			
												<?php if($tarea->estado == 1): ?>
												<span class="badge badge-default">Pendiente <i class="fa fa-circle text-warning"></i></span>
												<?php else: ?>
												<span class="badge badge-default">Completada <i class="fa fa-circle text-success"></i></span>
												<?php endif; ?>	
											</td>
											<td>
											<div scope="row" class="btn-group">
											<?php if($tarea->estado == 1): ?>
												<form method='GET' action="<?php echo e(url("/requerimientos/{$requerimiento->id}/tareas/{$tarea->id}/terminar")); ?>">					
													<?php echo e(csrf_field()); ?>

													<button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
													<input type="hidden" name="tarea" value=<?php echo e($tarea->id); ?>>
													<input type="hidden" name="req" value="<?php echo e($requerimiento->id); ?>">
												</form>
											<?php endif; ?>
											&nbsp;&nbsp;
											<?php if($tarea->estado == 1): ?>									
												<form method='HEAD' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/editar')); ?>">
													<?php echo e(csrf_field()); ?>

													<button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
												</form>
											<?php endif; ?>
											&nbsp;&nbsp;
											<?php if($tarea->estado == 1): ?>							
												<form method='POST' action="<?php echo e(url('/requerimientos/'.$requerimiento->id.'/tareas/'.$tarea->id.'/eliminar')); ?>">
													<?php echo e(csrf_field()); ?>

													<?php echo e(method_field('DELETE')); ?>						
													<button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
													<input type="hidden" name="tarea" value=<?php echo e($tarea->id); ?>>
													<input type="hidden" name="req" value=<?php echo e($requerimiento->id); ?>>
												</form>
											<?php endif; ?>
											</div>
											</td>
										<?php endif; ?>	
									</tr>						
									<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
									<?php endif; ?> 
								</tbody>	
							</table>
						</div>
					</div>
				</div>
				<?php endif; ?>				
				<br>
				<p><a href="<?php echo e(url('requerimientos')); ?>" class="btn btn-outline-primary"><i class="fa fa-arrow-left"></i> Regresar al listado</a></p>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
		if (window.innerWidth < 768) {
			$('.btn').addClass('btn-sm');
		}
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Requerimientos/show.blade.php ENDPATH**/ ?>