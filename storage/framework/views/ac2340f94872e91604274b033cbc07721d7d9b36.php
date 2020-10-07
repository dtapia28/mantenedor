<?php $__env->startSection('titulo', "Terminar Requerimiento"); ?>

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
        <h6>Por favor corrige los errores debajo:</h6>
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
        <li class="breadcrumb-item"><i class="fa fa-check"></i> Terminar Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Terminar Solicitud de Requerimiento</div>
                </div>
                <div class="ibox-body"> 
                    <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/guardar")); ?>">
                        <?php echo e(method_field('PUT')); ?>

                        <?php echo e(csrf_field()); ?>

                        <div class="col-sm-6 form-group">
                            <label for="cierre">Cierre</label>
                            <textarea class="form-control col-md-12" name="cierre"  placeholder="Texto del cierre" rows="5" cols="50"></textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label style="display: none" for='fechaCierre'>Fecha Real de Cierre (no obligatoria)</label>
                            <input style="display: none" class="form-control col-md-12" value="<?php echo e(old('fecha', $fecha->format("yy-m-d"))); ?>" type="date" name="fechaRealCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Terminar requerimiento</button>
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

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Requerimientos/terminado.blade.php ENDPATH**/ ?>