<?php $__env->startSection('titulo', "Crear Resolutor"); ?>

<?php $__env->startSection('contenido'); ?>
    <h1>Crear Resolutor</h1>

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
            <form method="POST" action="<?php echo e(url('resolutors/crear')); ?>">
                <?php echo e(csrf_field()); ?>


                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="nombreResolutor" id="name" value="<?php echo e(old('nombreResolutor')); ?>">
                <br>
                <label for="idTeam">Team:</label>                
                <select class="form-control col-md-6" name="idTeam">
                    <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup>
                            <option value=<?php echo e($team->id); ?>><?php echo e($team->nameTeam); ?></option>
                        </optgroup>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <br>
                <input type="checkbox" name="lider" value=1> Lider de Equipo
                <br>        
                <button class="btn btn-primary" type="submit">Crear Resolutor</button>
            </form>
        </div>
    </div>    
    <p>
        <a href="<?php echo e(url('resolutors')); ?>">Regresar al listado de resolutores</a>
    </p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Resolutors/create.blade.php ENDPATH**/ ?>