<?php $__env->startSection('titulo', "Tarea en Requerimiento"); ?>

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
    <h1 class="page-title"><i class="fa fa-address-card"></i> Crear tarea para Requerimiento <?php echo e($requerimiento->id2); ?></h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Tarea</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Tarea</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="<?php echo e(url('tareas/ingresar')); ?>">                
                        <?php echo e(csrf_field()); ?>

                        <div class="col-sm-6 form-group">
                            <label for='fechaSolicitud'>Fecha de Solicitud</label>
                            <input class="form-control col-md-12" type="date" name="fechaSolicitud">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha de Cierre</label>
                            <input class="form-control col-md-12" type="date" name="fechaCierre"> 
                        </div>
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
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="" for="textoRequerimiento">T&#237tulo Tarea</label>
                            <textarea id="titulo" class="form-control col-md-12" name="titulo" placeholder="T&#237tulo Tarea" rows="1" cols="50"></textarea>
                        </div>                        
                        <div class="col-sm-6 form-group">
                            <label class="" for="textoRequerimiento">Tarea</label>
                            <textarea id="texto" class="form-control col-md-12" name="texto" placeholder="Tarea" rows="5" cols="50"></textarea>
                            <input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo e($requerimiento->id); ?>">
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo e(url('/requerimientos/'.$requerimiento->id)); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar al requerimiento</a>
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
    $(document).ready(function(){
        $('#team').on('change', function(){
            var id_team = $(this).val();
            $.get('../../../requerimientos/script', {id_team: id_team}, function(resolutors){
                $('#resolutor').empty();
                $('#resolutor').append("<option value=''>Selecciona un resolutor</opcion>");
                $.each(resolutors, function(index, value){
                    $('#resolutor').append("<option value='"+index+"'>"+value+"</opcion>");
                });
            });
        });
        menu_activo('mRequerimientos');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Tareas/create.blade.php ENDPATH**/ ?>