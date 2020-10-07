<?php $__env->startSection('titulo', "Crear Solicitante"); ?>

<?php $__env->startSection('contenido'); ?>
    <h1>Crear Solicitante</h1>
    <br>
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
    <div class="form-row align-items-center">
        <div class="form-group col-md-8">  
            <form method="POST" action="<?php echo e(url('solicitantes/crear')); ?>">
                <?php echo e(csrf_field()); ?>


                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="nombreSolicitante" id="name" value="<?php echo e(old('nombreSolicitante')); ?>">
                <input type="hidden" name="volver" value="<?php echo e($volver); ?>">
                <br>
                <button class="btn btn-primary" type="submit">Crear Solicitante</button>
            </form>
        </div>
    </div>        
    <p>
        <a href="<?php echo e(url('solicitantes')); ?>">Regresar al listado de solicitantes</a>
    </p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Solicitantes/create.blade.php ENDPATH**/ ?>