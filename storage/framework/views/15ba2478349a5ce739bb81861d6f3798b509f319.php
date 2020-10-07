<?php $__env->startSection('titulo', "Ingresar Avance"); ?>

<?php $__env->startSection('contenido'); ?>
    <h1>Crear avance para requerimiento n° <?php echo e($requerimiento->id); ?></h1>
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
            <form method="POST" action="<?php echo e(url('avances/ingresar')); ?>">                
                <?php echo e(csrf_field()); ?>

                 <input type="hidden" id="idRequerimiento" name="idRequerimiento" value="<?php echo e($requerimiento->id); ?>">
                <label for='fechaCierre'>Fecha real de Cierre (no obligatoria):</label>
                <input class="form-control col-md-3" value="<?php echo e(old('fechaRealCierre', $requerimiento->fechaRealCierre)); ?>" type="date" name="fechaRealCierre">
                <br>
                <label for="porcentajeEjecutado">Porcentaje ejecutado:</label>
                <input class="form-control col-md-2" value="<?php echo e(old('porcentajeEjecutado', $requerimiento->porcentajeEjecutado)); ?>" type="number" name="porcentajeEjecutado">
                <br>                 
                <label class="" for="textAvance">Texto del avance:</label>
                <br>
                <textarea class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
                <br>
                <button class="btn btn-primary" type="submit">Guardar avance</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="/requerimientos/<?php echo e($requerimiento->id); ?>">Regresar al requerimiento</a>
    </p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Avances/create.blade.php ENDPATH**/ ?>