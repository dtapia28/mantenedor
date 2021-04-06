<?php $__env->startSection('titulo', "Editar Tarea"); ?>

<?php $__env->startSection('contenido'); ?>
<div class="page-heading">
<h1 class="page-title"><i class="fa fa-address-card"></i> Editar Tarea Requerimiento <?php echo e($tarea->id2); ?></h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-pencil"></i> Editar Tarea</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Registro de Tarea</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/tareas/{$tarea->id}")); ?>">
                        <?php echo e(method_field('PUT')); ?>

                        <?php echo e(csrf_field()); ?>

                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha de Solicitud</label>
                            <input value="<?php echo e($solicitud); ?>" class="form-control col-md-12" type="date" name="fechaSolicitud">
                        </div>
                        <div class="col-sm-6 form-group">       
                            <label for='fechaCierre'>Fecha de Cierre</label>
                            <input value="<?php echo e($cierre); ?>" class="form-control col-md-12" type="date" name="fechaCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="texto">Tarea:</label>
                            <textarea class="form-control col-md-12" name="texto" placeholder="Tarea" rows="5" cols="50"><?php echo e($tarea->textoTarea); ?></textarea>
                            <input type="hidden" name="tarea" value=<?php echo e($tarea->id); ?>>
                            <input type="hidden" name="req" value=<?php echo e($requerimiento->id); ?>>        
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Actualizar Registro</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo e(url('requerimientos/'.$requerimiento->id)); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar al requerimiento</a>
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

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Tareas/edit.blade.php ENDPATH**/ ?>