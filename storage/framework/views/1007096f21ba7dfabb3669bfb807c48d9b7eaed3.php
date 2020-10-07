<?php $__env->startSection('titulo', "Terminar Requerimiento"); ?>

<?php $__env->startSection('contenido'); ?>
    <h1>Terminar requerimiento</h1>

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
    <div class="p-4">
        <div class="form-group col-md-8">
        <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/guardar")); ?>">
            <?php echo e(method_field('PUT')); ?>

            <?php echo e(csrf_field()); ?>

            <label for="cierre">Cierre:</label>
            <br>
            <textarea class="form-control col-md-7" name="cierre"  placeholder="Texto del cierre" rows="5" cols="50"></textarea>
            <br>            
            <button class="btn btn-primary" type="submit">Terminar requerimiento</button>
            <br>
        </form>

	<p>
        <br>
		<a href="<?php echo e(url('requerimientos')); ?>">Volver al listado de requerimentos</a>
    </p>
<?php $__env->stopSection(); ?>    
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Requerimientos/terminado.blade.php ENDPATH**/ ?>