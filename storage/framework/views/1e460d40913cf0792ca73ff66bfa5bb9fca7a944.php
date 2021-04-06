<?php $__env->startSection('titulo', "Editar solicitante"); ?>
<?php $__env->startSection('contenido'); ?>
    <h1>Editar solicitante</h1>
    <br>
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
    <div class="form-row align-items-center">
        <div class="form-group col-md-8">  
            <form method="POST" action="<?php echo e(url("solicitantes/{$solicitante->id}")); ?>">
                <?php echo e(method_field('PUT')); ?>

                <?php echo e(csrf_field()); ?>


                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="nombreSolicitante" id="nombreSolicitante" value="<?php echo e(old('nombreSolicitante', $solicitante->nombreSolicitante)); ?>">
                <br>              
                <button class="btn btn-primary" type="submit">Actualizar solicitante</button>
            </form>
        </div>
    </div>    
	<p>
		<a href="<?php echo e(url('solicitantes')); ?>">Volver al listado de solicitantes</a>
    </p>
<?php $__env->stopSection(); ?>    
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Solicitantes/edit.blade.php ENDPATH**/ ?>