<?php $__env->startSection('contenido'); ?>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-table"></i>
            Requerimientos al día</div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Requerimiento</th>
                    <th>Fecha Solicitud</th>
                    <th>Fecha Cierre</th>
                    <th>Resolutor</th>
                    <th>Team</th>
                  </tr>
                </thead>
                <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $requerimientosGreen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requerimiento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
              <th id="tabla" scope="row">
                <a href="../public/requerimientos/<?php echo e($requerimiento->id); ?>">
                  <?php echo e($requerimiento->id2); ?>

                </a>            
              </th>
              <td width="350px" style="text-align:left;"> 
                <?php echo e($requerimiento->textoRequerimiento); ?>

              </td>       
              <td style="text-align: center;">  
                <?php echo e(date('d-m-Y', strtotime($requerimiento->fechaSolicitud))); ?>

              </td>
              <?php if($requerimiento->fechaRealCierre != ""): ?>
              <td width="100px" style="text-align: center;">  
                <?php echo e(date('d-m-Y', strtotime($requerimiento->fechaRealCierre))); ?>

              </td>
              <?php else: ?>             
              <td width="100px" style="text-align: center;">  
                <?php echo e(date('d-m-Y', strtotime($requerimiento->fechaCierre))); ?>

              </td>
              <?php endif; ?>
              <td width="100px" style="text-align: center">               
              <?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <?php if($requerimiento->resolutor == $resolutor->id): ?>     
                <?php echo e($resolutor->nombreResolutor); ?>

                <?php endif; ?>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
              <?php endif; ?> 
              </td>
              <?php $__empty_2 = true; $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                <?php if($requerimiento->resolutor == $resolutor->id): ?>  
                  <?php $__empty_3 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                    <?php if($resolutor->idTeam == $team->id): ?>
                    <td style="text-align: center">       
                    <?php echo e($team->nameTeam); ?>

                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                  <?php endif; ?>
                <?php endif; ?>  
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
              <?php endif; ?> 
              </td>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <?php endif; ?>                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/dashboard/green.blade.php ENDPATH**/ ?>