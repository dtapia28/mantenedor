<?php $__env->startSection('titulo', "Editar requerimiento"); ?>

<?php $__env->startSection('contenido'); ?>
<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <h6>Por favor corrige los siguientes errores:</h6>
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-pencil"></i> Editar Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Registro de Requerimiento</div>
                </div>
                <div class="ibox-body">  
                    <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}")); ?>">
                        <?php echo e(method_field('PUT')); ?>

                        <?php echo e(csrf_field()); ?>

                        <div class="col-sm-6 form-group">
                            <label for="textoRequerimiento">Texto del requerimiento:</label>
                            <textarea class="form-control col-md-12" name="textoRequerimiento" value="<?php echo e($requerimiento->textoRequerimiento); ?>" placeholder="Texto del requerimiento" rows="5" cols="50"><?php echo e($requerimiento->textoRequerimiento); ?></textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha de Solicitud:</label>
                            <input value="<?php echo e($solicitud); ?>" class="form-control col-md-12" type="date" name="fechaSolicitud">
                        </div>
                        <?php if($user[0]->nombre == "administrador"): ?>
                        <div id="fechaCierre" class="col-sm-6 form-group">
                            <input id="tipo_usuario" type="hidden" value=<?php echo e($user[0]->nombre); ?>>
                            <label for='fechaCierre'>Fecha de Cierre:</label>
                            <input value="<?php echo e($cierre); ?>" class="form-control col-md-12" type="date" name="fechaCierre">
                        </div>
                        <?php else: ?>
                        <div id="fechaCierre" class="col-sm-6 form-group" style="display: none;">
                            <input id="tipo_usuario" type="hidden" value=<?php echo e($user[0]->nombre); ?>>
                            <label for='fechaCierre'>Fecha de Cierre:</label>
                            <input value="<?php echo e($cierre); ?>" class="form-control col-md-12" type="date" name="fechaCierre">
                        </div>
                        <?php endif; ?>
                        <div class="col-sm-6 form-group">       
                            <label for="idSolicitante">Solicitante:</label>   
                            <select class="form-control col-md-12" name="idSolicitante">
                                <?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <optgroup>
                                        <option value="<?php echo e($solicitante->id); ?>" <?php if($solicitanteEspecifico['0']->id == $solicitante->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($solicitante->nombreSolicitante); ?>

                                        </option>
                                    </optgroup>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="idPrioridad">Prioridad:</label>   
                            <select class="form-control col-md-12" name="idPrioridad">
                                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <optgroup>
                                        <option value="<?php echo e($priority->id); ?>" <?php if($prioridadEspecifica['0']->id == $priority->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($priority->namePriority); ?></option>
                                    </optgroup>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
<!--                        <div class="col-sm-6 form-group">
                            <label for="idResolutor">Resolutor:</label>    
                            <select class="form-control col-md-12" name="resolutor">
                                <?php $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <optgroup>
                                        <option value="<?php echo e($resolutor->id); ?>" <?php if($resolutorEspecifico['0']->id == $resolutor->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($resolutor->nombreResolutor); ?></option>
                                    </optgroup>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>                     
                        </div>-->
                        <div class="col-sm-6 form-group">
                            <label for="team">Equipo Resolutores</label>
                            <select class="form-control col-md-12" id="team" name="team">
                                <option value="">Seleccione un Equipo</option>                    
                                <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value=<?php echo e($team->id); ?>><?php echo e($team->nameTeam); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='idResolutor'>Resolutor</label>                      
                            <select class='form-control col-md-12' id="resolutor" name='resolutor'>
                            </select>
                        </div>                        
                        <div class="col-sm-6 form-group">
                            <div id="creaAvance">
                                <label for="textAvance">Ingresar avance al requerimiento:</label>
                                <textarea class="form-control col-md-12" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>                    
                            </div>  
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Actualizar Registro</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="<?php echo e(url('requerimientos')); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="id_resolutor" name="id_resolutor" value=<?php echo e($id_resolutor); ?>>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
    });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script2'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#team').on('change', function(){
            var id_team = $(this).val();
            $.get('../script', {id_team: id_team}, function(resolutors){
                $('#resolutor').empty();
                $('#resolutor').append("<option value=''>Selecciona un resolutor</opcion>");
                $.each(resolutors, function(index, value){
                    $('#resolutor').append("<option value='"+index+"'>"+value+"</opcion>");
                });
            });
        });
        
        menu_activo('mRequerimientos');

        let textoReq       = sessionStorage.getItem('stRequerimiento'),
            fechaEmail     = sessionStorage.getItem('stFechaEmail'),
            fechaSolicitud = sessionStorage.getItem('stFechaSolicitud'),
            fechaCierre    = sessionStorage.getItem('stFechaCierre'),
            idGestor       = sessionStorage.getItem('stIdGestor'),
            idSolicitante  = sessionStorage.getItem('stIdSolicitante'),
            team           = sessionStorage.getItem('stTeam'),
            idResolutor    = sessionStorage.getItem('stIdResolutor'),
            idPrioridad    = sessionStorage.getItem('stIdPrioridad'),
            comentario     = sessionStorage.getItem('stComentario'),
            textAvance     = sessionStorage.getItem('stTextAvance');

        (textoReq!="" && textoReq!=null) ? $('#texto').val(textoReq) : $('#texto').val("");
        (fechaEmail!="" && fechaEmail!=null) ? $('#fechaEmail').val(fechaEmail) : $('#fechaEmail').val("");
        (fechaSolicitud!="" && fechaSolicitud!=null) ? $('#fechaSolicitud').val(fechaSolicitud) : $('#fechaSolicitud').val("");
        (fechaCierre!="" && fechaCierre!=null) ? $('#fechaCierre').val(fechaCierre) : $('#fechaCierre').val("");
        (idGestor!="" && idGestor!=null) ? $('#idGestor').val(idGestor) : $('#idGestor').val("");
        (idSolicitante!="" && idSolicitante!=null) ? $('#idSolicitante').val(idSolicitante) : $('#idSolicitante').val("");
        (team!="" && team!=null) ? $('#team').val(team) : $('#team').val("");
        if (team!="" && team!=null) {
            $.get('../requerimientos/script', {id_team: team}, function(resolutors){
                $('#resolutor').empty();
                $('#resolutor').append("<option value=''>Selecciona un resolutor</opcion>");
                $.each(resolutors, function(index, value){
                    $('#resolutor').append("<option value='"+index+"'>"+value+"</opcion>");
                });
            });
        }
        (idResolutor!="" && idResolutor!=null) ? $('#resolutor').val(idResolutor) : $('#resolutor').val("");
        (idPrioridad!="" && idPrioridad!=null) ? $('#idPrioridad').val(idPrioridad) : $('#idPrioridad').val("");
        (comentario!="" && comentario!=null) ? $('#comentario').val(comentario) : $('#comentario').val("");
        (textAvance!="" && textAvance!=null) ? $('#textAvance').val(textAvance) : $('#textAvance').val("");
    });

    function storeCacheForm() {
        if (typeof(Storage) !== 'undefined') {
            sessionStorage.setItem('stRequerimiento', $('#texto').val());
            sessionStorage.setItem('stFechaEmail', $('#fechaEmail').val());
            sessionStorage.setItem('stFechaSolicitud', $('#fechaSolicitud').val());
            sessionStorage.setItem('stFechaCierre', $('#fechaCierre').val());
            sessionStorage.setItem('stIdGestor', $('#idGestor').val());
            sessionStorage.setItem('stIdSolicitante', $('#idSolicitante').val());
            sessionStorage.setItem('stTeam', $('#team').val());
            sessionStorage.setItem('stIdResolutor', $('#resolutor').val());
            sessionStorage.setItem('stIdPrioridad', $('#idPrioridad').val());
            sessionStorage.setItem('stComentario', $('#comentario').val());
            sessionStorage.setItem('stTextAvance', $('#textAvance').val());
        }
    }
</script>
<script type="text/javascript">
    var id_resolutor = document.getElementById('id_resolutor').value;
    var tipo = document.getElementById('tipo_usuario').value;
    if(tipo != "administrador"){
        sessionStorage.setItem('id_resolutor', id_resolutor);
        var resolutor = document.getElementById('resolutor');
        resolutor.addEventListener('change', ()=>{
            var resultado = resolutor.value;
            var resolutor_store = sessionStorage.getItem('id_resolutor');

            if (resultado != resolutor_store) {
               document.getElementById('fechaCierre').style.display = "block"; 
            } else {
                document.getElementById('fechaCierre').style.display = "none";
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Requerimientos/edit.blade.php ENDPATH**/ ?>