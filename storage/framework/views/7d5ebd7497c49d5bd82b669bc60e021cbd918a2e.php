<?php $__env->startSection('titulo', "Crear Prioridad"); ?>

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
    <h1 class="page-title"><i class="fa fa-sort-amount-desc"></i> Prioridades</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Prioridad</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Prioridad</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="<?php echo e(url('priorities/crear')); ?>">
                        <?php echo e(csrf_field()); ?>

                        <div class="form-group row">
                            <label for="name" class="col-md-2 text-md-right">Nombre</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" name="namePriority" id="name" value="<?php echo e(old('namePriority')); ?>">
                                <input type="hidden" name="volver" value="<?php echo e($volver); ?>">
                            </div>
                        </div>             
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-5">
                                    <?php if(strpos(url()->full(), 'volver=1')): ?>
                                        <input type="hidden" id="volver" name="volver" value="1">
                                        <a href="<?php echo e(url('requerimientos/nuevo')); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
                                    <?php else: ?>
                                        <input type="hidden" id="volver" name="volver" value="0">
                                        <a href="<?php echo e(url('priorities')); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
                                    <?php endif; ?>
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
        menu_activo('mPrioridades');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Priorities/create.blade.php ENDPATH**/ ?>