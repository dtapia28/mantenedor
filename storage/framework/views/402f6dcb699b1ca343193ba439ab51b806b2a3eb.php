<?php $__env->startSection('titulo', "Editar requerimiento"); ?>

<?php $__env->startSection('contenido'); ?>
    <h1>Editar requerimiento</h1>

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
        <form method="POST" action="<?php echo e(url("requerimientos/{$requerimiento->id}")); ?>">
            <?php echo e(method_field('PUT')); ?>

            <?php echo e(csrf_field()); ?>


            <label for="textoRequerimiento">Texto del requerimiento:</label>
            <br>
            <textarea class="form-control col-md-7" name="textoRequerimiento" value='<?php echo e(old('textoRequerimiento', $requerimiento->textoRequerimiento)); ?>' placeholder="Texto del requerimiento" rows="5" cols="50"></textarea>
            <label>Texto de requerimiento: <?php echo e($requerimiento->textoRequerimiento); ?></label>
            <br>
            <label for='fechaSolicitud'>Fecha de Cierre:</label>
            <input value="<?php echo e(old('fechaCierre', $requerimiento->fechaCierre)); ?>" class="form-control col-md-3" type="date" name="fechaCierre">
            <br>            
            <label for="idSolicitante">Solicitante:</label> 
            <br>       
            <select class="form-control col-md-3" name="idSolicitante">
                <?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup>
                        <option value="<?php echo e(old('idSolicitante', $solicitante->id)); ?>"><?php echo e($solicitante->nombreSolicitante); ?></option>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>
            <label for="idPrioridad">Prioridad:</label>   
            <br>         
            <select class="form-control col-md-3" name="idPrioridad">
                <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup>
                        <option value="<?php echo e(old('idPrioridad', $priority->id)); ?>"><?php echo e($priority->namePriority); ?></option>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>
            <label for="idResolutor">Resolutor:</label>
            <br>        
            <select class="form-control col-md-3" name="idResolutor">
                <?php $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <optgroup>
                        <option value="<?php echo e(old('idResolutor', $resolutor->id)); ?>"><?php echo e($resolutor->nombreResolutor); ?></option>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>                      
            <br>            
            <button class="btn btn-primary" type="submit">Actualizar requerimiento</button>
            <br>
        </form>

	<p>
        <br>
		<a href="<?php echo e(url('requerimientos')); ?>">Volver al listado de requerimentos</a>
    </p>
<?php $__env->stopSection(); ?>    
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Requerimientos/edit.blade.php ENDPATH**/ ?>