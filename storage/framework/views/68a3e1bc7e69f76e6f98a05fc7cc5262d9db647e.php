<?php $__env->startSection('titulo', "Editar avance"); ?>
<?php $__env->startSection('contenido'); ?>
    <h1>Editar avance</h1>
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
            <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}/avances/{$avance->id}")); ?>">
            	<?php echo e(method_field('PUT')); ?>           
                <?php echo e(csrf_field()); ?>

                 <input type="hidden" id="idAvance" name="idAvance" value="<?php echo e($avance->id); ?>">               
                <label class="" for="textAvance">Texto del avance:</label>
                <br>
                <textarea class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
            	<label>Texto del avance: <?php echo e($avance->textAvance); ?></label>                
                <br>
                <button class="btn btn-primary" type="submit">Actualizar avance</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="/requerimientos/<?php echo e($requerimiento->id); ?>">Regresar al requerimiento</a>
    </p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Avances/edit.blade.php ENDPATH**/ ?>