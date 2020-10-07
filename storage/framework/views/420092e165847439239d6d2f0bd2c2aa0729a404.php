<?php $__env->startSection('titulo', "Ingresar Avance"); ?>

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
    <h1 class="page-title"><i class="fa fa-address-card"></i> Avance Requerimiento <?php echo e($requerimiento->id2); ?></h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Avance</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Avance</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="<?php echo e(url('avances/ingresar')); ?>">                
                        <?php echo e(csrf_field()); ?>

                        <div class="col-sm-6 form-group">
                            <input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo e($requerimiento->id); ?>">
                            <label for='fechaCierre'>Fecha real de Cierre (no obligatoria)</label>
                            <input class="form-control col-md-12" value="<?php echo e(old('fechaRealCierre', $requerimiento->fechaRealCierre)); ?>" type="date" name="fechaRealCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="porcentajeEjecutado">Porcentaje ejecutado</label>
                            <input class="form-control col-md-12" value="<?php echo e(old('porcentajeEjecutado', $requerimiento->porcentajeEjecutado)); ?>" type="number" name="porcentajeEjecutado">
                        </div>
                        <div class="col-sm-6 form-group">    
                            <label class="" for="textAvance">Texto del avance</label>
                            <textarea class="form-control col-md-12" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
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

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Avances/create.blade.php ENDPATH**/ ?>