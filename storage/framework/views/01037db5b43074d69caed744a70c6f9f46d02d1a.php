<?php $__env->startSection('titulo', "Log de requerimiento"); ?>
<?php $__env->startSection('contenido'); ?>
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-file-text-o"></i> Log del Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php if($id2 == "INC"): ?>
                            <h2>Log Incidente <?php echo e($requerimiento->id2); ?></h2>
                            <?php else: ?>
                            <h2>Log Requerimiento <?php echo e($requerimiento->id2); ?></h2>
                            <?php endif; ?>
                            <br>
                            <table class="table table-condensed">	
                                <tr>
                                    <td width="40%"><strong>Creado por</strong></td>
                                    <td width="60%"><?php echo e($creador->name); ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Creado el día</strong></td>
                                    <td><?php echo e($fecha_creacion); ?></td>
                                </tr>
                                <?php if(count($elementos_log)!= 0): ?>
                                    <?php $__currentLoopData = $elementos_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($log->tipo == "edición"): ?>
                                            <tr>
                                                <td width="40%"><strong>Edición de <?php echo e($log->campo); ?></strong></td>
                                                <?php $__currentLoopData = $usuarios_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($log->idUsuario == $usuario->idUser): ?>
                                                        <td width="60%"><?php echo e($usuario->name); ?></td>
                                                        <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha de edición</strong></td>
                                                <td><?php echo e($log->created_at->format("d-m-Y")); ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $elementos_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($log->tipo == "terminar"): ?>
                                            <tr>
                                                <td width="40%"><strong>Terminado por</strong></td>
                                                <?php $__currentLoopData = $usuarios_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($log->idUsuario == $usuario->idUser): ?>
                                                        <td width="60%"><?php echo e($usuario->name); ?></td>
                                                        <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                    
                                            </tr>
                                            <tr>
                                                <td><strong>Terminado el día</strong></td>
                                                <td><?php echo e($log->created_at->format("d-m-Y")); ?></td>
                                            </tr>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php $__currentLoopData = $elementos_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($log->tipo == "autorizar"): ?>
                                            <tr>
                                                <td width="40%"><strong>Autorizado por</strong></td>
                                                <?php $__currentLoopData = $usuarios_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($log->idUsuario == $usuario->idUser): ?>
                                                        <td width="60%"><?php echo e($usuario->name); ?></td>
                                                        <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                    
                                            </tr>
                                            <tr>
                                                <td><strong>Autorizado el día</strong></td>
                                                <td><?php echo e($log->created_at->format("d-m-Y")); ?></td>
                                            </tr>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                     
                                    <?php $__currentLoopData = $elementos_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($log->tipo == "rechazar"): ?>
                                            <tr>
                                                <td width="40%"><strong>Rechazado por</strong></td>
                                                <?php $__currentLoopData = $usuarios_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($log->idUsuario == $usuario->idUser): ?>
                                                        <td width="60%"><?php echo e($usuario->name); ?></td>
                                                        <?php break; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                    
                                            </tr>
                                            <tr>
                                                <td><strong>Rechazado el día</strong></td>
                                                <td><?php echo e($log->created_at->format("d-m-Y")); ?></td>
                                            </tr>
                                            <?php break; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>                                    
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
        if (window.innerWidth < 768) {
            $('.btn').addClass('btn-sm');
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Requerimientos/log.blade.php ENDPATH**/ ?>