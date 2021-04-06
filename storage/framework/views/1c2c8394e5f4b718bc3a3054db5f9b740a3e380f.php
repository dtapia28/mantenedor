<?php $__env->startSection('titulo', "Editar resolutor"); ?>

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
    <h1 class="page-title"><i class="fa fa-address-book"></i> Resolutores</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-pencil"></i> Editar Resolutor</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Registro de Resolutor</div>
                </div>
                <div class="ibox-body"> 
                    <form method="POST" action="<?php echo e(url("resolutors/{$resolutor->id}")); ?>">
                        <?php echo e(method_field('PUT')); ?>

                        <?php echo e(csrf_field()); ?>

                        <div class="form-group row">
                            <label for="name" class="col-md-2 text-md-right">Equipo</label>
                            <div class="col-md-4">       
                            <select class="form-control" name="idTeam">
                                <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($team->id); ?>" <?php if($equipo == $team->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($team->nameTeam); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Actualizar Registro</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="<?php echo e(url('resolutors')); ?>" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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
        menu_activo('mResolutores');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Resolutors/edit.blade.php ENDPATH**/ ?>