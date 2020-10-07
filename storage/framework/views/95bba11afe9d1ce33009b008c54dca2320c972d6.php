<?php $__env->startSection('titulo', "Crear Requerimiento"); ?>

<?php $__env->startSection('contenido'); ?>
<?php if(session()->has('msj')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('msj')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
<?php endif; ?>
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
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Requerimiento</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="<?php echo e(url('requerimientos/crear')); ?>">
                        <?php echo e(csrf_field()); ?>

                        <div class="col-sm-6 form-group">
                            <label for='idTipo'>Tipo</label>               
                            <select class="form-control col-md-12" name="idTipo" id="idTipo">
                                <option value=1>Requerimiento</option>
                                <option value=2>Incidente</option>
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="" for="textoRequerimiento">Solicitud</label>
                            <textarea id="texto" class="form-control col-md-12" name="textoRequerimiento" placeholder="Solicitud" rows="5" cols="50"></textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaEmail'>Fecha original de requerimiento</label>
                            <input class="form-control col-md-12" type="date" name="fechaEmail" id="fechaEmail">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaSolicitud'>Fecha inicio de seguimiento</label>
                            <input class="form-control col-md-12" type="date" name="fechaSolicitud" id="fechaSolicitud">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha solicitada de cierre</label>
                            <input class="form-control col-md-12" type="date" name="fechaCierre" id="fechaCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='idGestor'>Gestor</label>               
                            <select class="form-control col-md-12" name="idGestor" id="idGestor">
                                <?php $__currentLoopData = $gestores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gestor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value=<?php echo e($gestor->id); ?>><?php echo e($gestor->nombreResolutor); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php if($user[0]->nombre != "solicitante"): ?>
                        <div class="col-sm-6 form-group">
                            <label for="idSolicitante">Solicitante</label>
                            <select class="form-control col-md-12" name="idSolicitante" id="idSolicitante">
                                <?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value=<?php echo e($solicitante->id); ?>><?php echo e($solicitante->nombreSolicitante); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php if($user[0]->nombre=="administrador"): ?>
                            <a href="<?php echo e(url('/users/nuevo')); ?>?volver=1" onclick="storeCacheForm()">Crear Solicitante</a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
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
                            <select class='form-control col-md-12' id="resolutor" name='idResolutor'>
                            </select>
                            <?php if($user[0]->nombre=="administrador"): ?>
                            <a href='<?php echo e(url('/users/nuevo')); ?>?volver=1' onclick="storeCacheForm()">Crear Resolutor</a>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="idPrioridad">Prioridad</label>        
                            <select class="form-control col-md-12" name="idPrioridad" id="idPrioridad">
                                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value=<?php echo e($priority->id); ?>><?php echo e($priority->namePriority); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php if($user[0]->nombre=="administrador"): ?>
                            <a href="<?php echo e(url('/priorities/nueva')); ?>?volver=1" onclick="storeCacheForm()">Crear Prioridad</a>
                            <?php endif; ?>
                        </div>
                        <div class="col-sm-6 form-group">
                            <div id="creaComentario">
                                <label for="textComentario">Ingresar comentario para resolutor</label>
                                <textarea class="form-control col-md-12" name="comentario" placeholder="Comentario para resolutor" rows="5" cols="50" id="comentario"></textarea>                     
                            </div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <div id="creaAvance">
                                <label for="textAvance">Ingresar avance al requerimiento</label>
                                <textarea class="form-control col-md-12" name="textAvance" id="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>                    
                            </div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" onclick="storeCacheForm()" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script2'); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#team').on('change', function(){
            var id_team = $(this).val();
            $.get('../requerimientos/script', {id_team: id_team}, function(resolutors){
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
            textAvance     = sessionStorage.getItem('stTextAvance'),
            tipo           = sessionStorage.getItem('stTipo');

        (textoReq!="" && textoReq!=null) ? $('#texto').val(textoReq) : $('#texto').val("");
        (fechaEmail!="" && fechaEmail!=null) ? $('#fechaEmail').val(fechaEmail) : $('#fechaEmail').val("");
        (fechaSolicitud!="" && fechaSolicitud!=null) ? $('#fechaSolicitud').val(fechaSolicitud) : $('#fechaSolicitud').val("");
        (fechaCierre!="" && fechaCierre!=null) ? $('#fechaCierre').val(fechaCierre) : $('#fechaCierre').val("");
        (idGestor!="" && idGestor!=null) ? $('#idGestor').val(idGestor) : $('#idGestor').val("");
        (idSolicitante!="" && idSolicitante!=null) ? $('#idSolicitante').val(idSolicitante) : $('#idSolicitante').val("");
        (team!="" && team!=null) ? $('#team').val(team) : $('#team').val("");
        (tipo!="" && tipo!=null) ? $('#tipo').val(tipo) : $('#tipo').val("");
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
            sessionStorage.setItem('stTipo', $('#tipo').val());
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Requerimientos/create.blade.php ENDPATH**/ ?>