<?php $__env->startSection('titulo', "Crear Requerimiento"); ?>

<?php $__env->startSection('contenido'); ?>
    <header>
    <h1>Crear Requerimiento</h1>
    </header>
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
            <form method="POST" action="<?php echo e(url('requerimientos/crear')); ?>">
                <?php echo e(csrf_field()); ?>


                <label class="" for="textoRequerimiento">Texto del requerimiento:</label>
                <br>
                <textarea class="form-control col-md-7" name="textoRequerimiento" placeholder="Texto del requerimiento" rows="5" cols="50"></textarea>
                <br>
                <label for='fechaEmail'>Fecha de Email:</label>
                <input class="form-control col-md-3" type="date" name="fechaEmail">
                <br>
                <label for='fechaSolicitud'>Fecha de Solicitud:</label>
                <input class="form-control col-md-3" type="date" name="fechaSolicitud">
                <br>
                <label for='fechaCierre'>Fecha de Cierre:</label>
                <input class="form-control col-md-3" type="date" name="fechaCierre">
                <br>
                <label for="idSolicitante">Solicitante:</label>
                <br>
                <select class="form-control col-md-3" name="idSolicitante">
                    <?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup>
                            <option value=<?php echo e($solicitante->id); ?>><?php echo e($solicitante->nombreSolicitante); ?></option>
                        </optgroup>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <br>
                <label for="idResolutor">Resolutor:</label>        
                <br>                 
                <select class="form-control col-md-3" name="idResolutor">
                    <?php $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup>
                            <option value=<?php echo e($resolutor->id); ?>><?php echo e($resolutor->nombreResolutor); ?></option>
                        </optgroup>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <br>
                <label for="idPrioridad">Prioridad:</label>        
                <br>                 
                <select class="form-control col-md-3" name="idPrioridad">
                    <?php $__currentLoopData = $priorities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $priority): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup>
                            <option value=<?php echo e($priority->id); ?>><?php echo e($priority->namePriority); ?></option>
                        </optgroup>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>             
                <br>
                <br>
                <button class="btn btn-primary" type="submit">Crear Requerimiento</button>        
            </form>
        </div>
    </div>    
    <br>
    <p>
        <a href="../requerimientos">Regresar al listado de requerimientos</a>
    </p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Requerimientos/create.blade.php ENDPATH**/ ?>