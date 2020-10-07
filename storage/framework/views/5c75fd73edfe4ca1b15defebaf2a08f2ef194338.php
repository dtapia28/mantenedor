<?php $__env->startSection('titulo', "Rechazar Requerimiento"); ?>

<?php $__env->startSection('contenido'); ?>
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
        <li class="breadcrumb-item"><i class="fa fa-close"></i> Rechazar Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Rechazar Solicitud de Requerimiento</div>
                </div>
                <div class="ibox-body"> 
                    <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento}/rechazar")); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="col-sm-6 form-group">
                            <label for="cierre">Motivo del rechazo</label>
                            <textarea class="form-control col-md-12" name="rechazo" placeholder="Texto del rechazo" rows="5" cols="50" required></textarea>
                            <input type="hidden" value="<?php echo e($requerimiento); ?>" name="requerimiento" id="requerimiento">
                            <input type="hidden" value="<?php echo e($estado); ?>" name="fActivo" id="fActivo">
                            <input type="hidden" value="" name="fState" id="fState">
                            <input type="hidden" value="" name="fValor" id="fValor">
                            <input type="hidden" value="" name="fSolicitante" id="fSolicitante">                         
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Rechazar requerimiento</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="<?php echo e(url("requerimientos/{$requerimiento}")); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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

        if (sessionStorage.getItem('stFiltroActivo') == 1) {
            $("#fActivo").val(sessionStorage.getItem('stFiltroActivo'));
            $("#fState").val(sessionStorage.getItem('stState'));
            $("#fValor").val(sessionStorage.getItem('stValor'));
            $("#fSolicitante").val(sessionStorage.getItem('stSolicitante'));
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Requerimientos/rechazar.blade.php ENDPATH**/ ?>