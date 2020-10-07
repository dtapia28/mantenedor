    
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('vendor/DataTables/datatables.min.css')); ?>" rel="stylesheet" />
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
    <form class="navbar-form navbar-left pull-right" method='GET' action="<?php echo e(url('requerimientos/')); ?>">
        <select id="state" class="custom-select" name="state">
            <option selected="true" disabled="disabled" value="">Escoja una opción...</option>
            <?php if($user[0]->nombre == "administrador" or $user[0]->nombre == "supervisor"): ?>
            <option value="6">Por autorizar</option>
            <?php endif; ?>
            <?php if($user[0]->nombre == "resolutor"): ?>
            <?php if($user2->lider == 1): ?>
            <option value="6">Por autorizar</option>
            <?php endif; ?>			
            <?php endif; ?>                        
            <?php if($user[0]->nombre == "resolutor"): ?>
            <option value="7">Esperando autorización</option>
            <?php endif; ?>
            <?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador"): ?>
            <option value="8">Rechazados</option>
            <?php endif; ?>	                        
            <option value="0">Inactivo</option>
            <option value="1">Activo</option>
            <option value="2">% mayor o igual que</option>
            <option value="3">% menor o igual que</option>
            <option value="4">Vencidos</option>
            <option value="5">Por solicitante</option>     	
        </select>
        <input class="form-control col-md-12" type="number" id="valor" style="display: none" name="valorN" placeholder="porcentaje avance">
        <select id='solicitante' class='form-control col-md-12' name='solicitante' style='display: none;'>
            <optgroup>
                <?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value=<?php echo e($solicitante->id); ?>><?php echo e($solicitante->nombreSolicitante); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>							
            </optgroup>
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
            <?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
            <div class="pull-right"><a class="btn btn-success" href="<?php echo e(url('requerimientos/nuevo')); ?>" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Requerimiento</a></div>
            <?php endif; ?>	                        
        </div>
        <div class="ibox-body">	
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <?php if($state == 0 or $state == 8): ?>
                            <th>Activar</th>
                            <?php endif; ?>
                            <?php if($state == 7): ?>
                            <th>Activar</th>
                            <?php endif; ?>						
                            <th>Id</th>
                            <th>Requerimiento</th>
                            <th>Fecha Solicitud</th>
                            <th>Fecha Cierre</th>
                            <th>Resolutor</th>
                            <th>Ejecutado (%)</th>
                            <?php if($state != 0): ?>
                            <th>Estatus</th>
                            <?php endif; ?>
                            <?php if($state != 6 and $state !=0): ?>
                            <?php if($user[0]->nombre!="supervisor"): ?>                                                        
                            <th>Anidados</th>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php if($state != 6 and $state != 7 and $state != 8 and $state != 0): ?>
                            <th>Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody style="font-size:13px">
                        <?php $__empty_1 = true; $__currentLoopData = $requerimientos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <?php if($state == 0 or $state == 8): ?>
                            <td id="tabla" scope="row">
                                <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/activar")); ?>">
                                    <?php echo e(csrf_field()); ?>

                                    <button onclick="return confirm('¿Estás seguro/a de activar el requerimiento?')" type="submit" value="Activar" class="btn btn-success" name="">Activar</button>
                                </form>
                            </td>
                            <?php endif; ?>
                            <?php if($state == 7): ?>
                            <th id="tabla" scope="row">
                                <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/activar")); ?>">
                                    <?php echo e(csrf_field()); ?>

                                    <button onclick="return confirm('¿Estás seguro/a de activar el requerimiento?')" type="submit" value="Nuevo Requerimiento" class="btn btn-success" name="">Activar</button>
                                </form>
                            </th>
                            <?php endif; ?>                                                        
                            <td style="white-space: nowrap;">
                                <a href="<?php echo e(url("requerimientos/{$requerimiento->id}")); ?>">
                                    <?php echo e($requerimiento->id2); ?>

                                </a>					
                            </td>
                            <td>	
                                <?php echo e($requerimiento->textoRequerimiento); ?>

                            </td>				
                            <td>	
                                <?php echo e(date('Y-m-d', strtotime($requerimiento->fechaSolicitud))); ?>

                            </td>
                            <?php if($requerimiento->fechaRealCierre != ""): ?>
                            <td>	
                                <?php echo e(date('Y-m-d', strtotime($requerimiento->fechaRealCierre))); ?>

                            </td>
                            <?php else: ?>
                            <td>	
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
                                <span class="badge badge-default">Al día <i class="fa fa-circle text-success"></i></span>
                                <?php elseif($requerimiento->status == 2): ?>
                                <span class="badge badge-default">Por Vencer <i class="fa fa-circle text-warning"></i></span>
                                <?php else: ?>
                                <span class="badge badge-default">Vencido <i class="fa fa-circle text-danger"></i></span>
                                <?php endif; ?>					
                            </td>
                            <?php endif; ?>
                            <?php if($state != 6 && $state !=0): ?>
                            <?php if($user[0]->nombre!="supervisor"): ?>
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
                            <?php endif; ?>
                            <?php if($state != 6 and $state != 7 and $state != 8 and $state != 0): ?>
                            <td>
                                <div scope="row" class="btn-group">
                                    <?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador" or $user[0]->nombre == "supervisor"): ?>
                                    <?php if($user[0]->nombre == "resolutor" and $res->id == $requerimiento->resolutor): ?>
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/anidar")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($user[0]->nombre == "supervisor" and $res->id == $requerimiento->resolutor): ?>
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/anidar")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($user[0]->nombre == "administrador"): ?>
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/anidar")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-warning btn-sm" data-toggle="tooltip" data-original-title="Anidar" style="cursor:pointer"><i class="fa fa-compress"></i></button>
                                    </form>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if($user[0]->nombre == "resolutor" or $user[0]->nombre == "administrador" or $user[0]->nombre == "supervisor"): ?>
                                    <?php if($user[0]->nombre == "resolutor" and $res->id == $requerimiento->resolutor): ?>
                                    &nbsp;
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/terminado")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($user[0]->nombre == "supervisor" and $res->id == $requerimiento->resolutor): ?>
                                    &nbsp;
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/terminado")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-success btn-sm" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if( $user[0]->nombre == "administrador"): ?>
                                    &nbsp;
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/terminado")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-success btn-sm btn-terminar" data-toggle="tooltip" data-original-title="Terminar" style="cursor:pointer"><i class="fa fa-check"></i></button>
                                    </form>
                                    <?php endif; ?>
                                    <?php endif; ?>    
                                    <?php if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador"): ?>
                                    &nbsp;
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/editar")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
                                    </form>
                                    <?php endif; ?>
                                    <?php if($user[0]->nombre == "resolutor" and $lider == 1): ?>
                                    &nbsp;
                                    <form method='HEAD' action="<?php echo e(url("requerimientos/{$requerimiento->id}/editar")); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <button type="submit" class="btn btn-primary btn-sm" data-toggle="tooltip" data-original-title="Editar" style="cursor:pointer"><i class="fa fa-pencil"></i></button>
                                    </form>
                                    <?php endif; ?>                                    
                                    <?php if($user[0]->nombre == "solicitante" or $user[0]->nombre == "administrador"): ?>
                                    &nbsp;
                                    <form method='POST' action="<?php echo e(url('/requerimientos/'.$requerimiento->id)); ?>">
                                        <?php echo e(csrf_field()); ?>

                                        <?php echo e(method_field('DELETE')); ?>						
                                        <button type="submit" class="btn btn-danger btn-sm" data-toggle="tooltip" data-original-title="Eliminar" style="cursor:pointer"><i class="fa fa-trash"></i></button>
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
</div>
<?php $__env->stopSection(); ?>
    
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(asset('vendor/DataTables/datatables.min.js')); ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        sessionStorage.clear();
        $('#state').on('change', function(){
            var role = $(this).val();
            if(role == 2 || role ==3){
                document.getElementById("valor").style.display = "block";
                document.getElementById("solicitante").style.display = "none";
            } else if (role == 5){
                document.getElementById("solicitante").style.display = "block";
                document.getElementById("valor").style.display = "none";
            } else {
                document.getElementById("valor").style.display = "none";
                document.getElementById("solicitante").style.display = "none";	
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
            stateSave: true,
        });
    });
    function confirmar(){
        var respuesta = confirm("¿Estás seguro/a que desea activar el requerimiento?");
        if (respuesta == true) {
            return true;
        } else {
            return false;
        }
    }
</script>		
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Requerimientos/index.blade.php ENDPATH**/ ?>