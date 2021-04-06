<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
<div class="ibox" style="background-color: #E7EDF5;">
    <div style=" opacity:0.3; padding: 10px; text-align: center;">
        <a href="https://app.kinchika.com/login"><img src="<?php echo e(asset('img/logo-blue.png')); ?>" alt="logo"></a>
    </div>
    <div class="ibox-head">
        <div class="ibox-title" align="center"><h1>Reporte Requerimientos</h1></div>       
    </div>
    <div class="ibox-body">
        <style>
            thead th, td {
                border: 1px solid black;
                text-align: center;
            }
            .head_table{
                width: 120rem;
                font-size: 2.5rem;
                text-align: left;
            }
            
            tbody th{
                border: 1px solid black;
                text-align: left;
            }
            table,td {
                border: 1px solid black;
            }
            .aj-auto {
                width: auto;
            }
            .bg-verde {
                background-color: #28a745;
            }
            .bg-rojo {
                background-color: #ff3333;
            }
            .bg-morado {
                background-color: #7733ff;
            }
            .bg-amarillo {
                background-color: #fcdf00;
            }
        </style>
        <table align="center" cellpadding="5" cellspacing="5" style="font-family: Arial, Helvetica, sans-serif" border="0">
            <tr >
                <td class="head_table"><h2>Requerimientos al <?php echo e($hoy); ?></h2></td>
            </tr>
            <table border="0" style="border: 1px solid #343a40;" cellpadding="5" cellspacing="0">
                <thead>
                    <tr style="color: #f8f9fa; background-color: #343a40; border: 1px solid #f8f9fa;">
                        <th class="aj-auto">Area</th>
                        <th class="aj-auto">Resolutor</th>
                        <th class="aj-auto">Cant. de RQ activos<br>al <?php echo e($ayer); ?></th>
                        <th class="aj-auto">Vencidos</th>
                        <th class="aj-auto">Cant. de RQ generados<br>el día de hoy <?php echo e($hoy); ?></th>
                        <th class="aj-auto">Cant. de RQ cerrados<br>el día de hoy <?php echo e($hoy); ?></th>
                        <th class="aj-auto">Cant. de RQ activos <br>al <?php echo e($hoy); ?></th>
                        <th class="aj-auto">Color según cant.<br>de RQ vencidos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0; $i<count((array)$valores["resolutores_array"]); $i++): ?>
                            <tr>
                                <td style="border: 1px solid #343a40;"><?php echo e($valores['equipos_array'][$i]); ?></td>
                                <td style="border: 1px solid #343a40;"><?php echo e($valores['resolutores_array'][$i]); ?></td>
                                <td style="text-align: center; border: 1px solid #343a40;"><?php echo e($valores['pendientes_resolutor_hoy'][$i]+$valores['cerrados'][$i]-$valores['creadoHoy_resolutor'][$i]); ?></td>
                                <?php if($valores['vencidos'][$i] >= 1): ?>
                                <td style="text-align: center; color: red; font-weight: bold; border: 1px solid #343a40;"><?php echo e($valores['vencidos'][$i]); ?></td>
                                <?php else: ?>
                                <td style="text-align: center; border: 1px solid #343a40;"><?php echo e($valores['vencidos'][$i]); ?></td>
                                <?php endif; ?>
                                <td style="text-align: center; border: 1px solid #343a40;"><?php echo e($valores['creadoHoy_resolutor'][$i]); ?></td>
                                <td style="text-align: center; border: 1px solid #343a40;"><?php echo e($valores['cerrados'][$i]); ?></td>
                                <td style="text-align: center; border: 1px solid #343a40;"><?php echo e($valores['pendientes_resolutor_hoy'][$i]); ?></td>
                                <?php if($valores['vencidos'][$i] >= 1 and $valores['vencidos'][$i]<=3): ?>
                                <td class="bg-amarillo" style="border: 1px solid #343a40; background-color: yellow;"></td>
                                <?php elseif($valores['vencidos'][$i]>=4 and $valores['vencidos'][$i]<=6): ?>
                                <td class="bg-rojo" style="background-color: #dc3545; border: 1px solid #343a40; background-color: red;"></td>
                                <?php elseif($valores['vencidos'][$i]>=7): ?>
                                <td class="bg-morado" style="font-weight: bold; color: white; text-align: center; border: 1px solid #343a40; background-color: #45046a;">GRAVE</td>
                                <?php else: ?>
                                <td class="bg-verde" style="border: 1px solid #343a40; background-color: green;"></td>
                                <?php endif; ?>                               
                            </tr>
                    <?php endfor; ?>
                    <tr>
                        <td colspan="2" style="font-weight: bold; border: 1px solid #343a40;">Total RQ</td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;"><?php echo e($valores['total_activos_ayer']); ?></td>
                        <td style="color: red; font-weight: bold; text-align: center; border: 1px solid #343a40;"><?php echo e($valores['total_vencidos']); ?></td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;"><?php echo e($valores['total_creados_hoy']); ?></td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;"><?php echo e($valores['total_cerrados_hoy']); ?></td>
                        <td style="font-weight: bold; text-align: center; border: 1px solid #343a40;"><?php echo e($valores['total_activos_hoy']); ?></td>
                    </tr>
                </tbody>
            </table>
        </table>
        <br>
        <table align="center" border="0" style="border: 1px solid #343a40;" cellpadding="5" cellspacing="0">
            <thead>
                <tr style="color: #f8f9fa; background-color: #343a40; border: 1px solid #343a40;">
                    <th class="aj-auto">Semaforo</th>
                    <?php $__currentLoopData = $valores["dias"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="aj-auto"><?php echo e($dia); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
            <?php for($i=0; $i < count((array)$valores["resolutores_array"]); $i++): ?>
            <?php $__currentLoopData = $valores['dias']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($valores['dias'][0] == $dia): ?>
            <tr>
                <td class="aj-auto" style="border: 1px solid #343a40; white-space: nowrap;"><?php echo e($valores['resolutores_array'][$i]); ?></td>
            <?php endif; ?>
                <?php $__currentLoopData = $valores['todo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $elemento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $valores['array_id_resolutor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor): ?>
                            <?php if($elemento->nota >= 1 and $elemento->nota <=3): ?>
                                <td class="bg-amarillo" style="border: 1px solid #343a40; background-color: yellow"></td>
                            <?php elseif($elemento->nota >=4 and $elemento->nota <=6): ?>
                                <td class="bg-rojo" style="border: 1px solid #343a40; background-color: red"></td> 
                            <?php elseif($elemento->nota >=7): ?>
                                <td class="bg-morado" style="font-weight: bold; color: white; text-align: center; border: 1px solid #343a40; background-color: #45046a;">GRAVE</td>
                            <?php else: ?>
                                <td class="bg-verde" style="border: 1px solid #343a40; background-color: green"></td>
                            <?php endif; ?>
                         <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <?php endfor; ?>
            </tbody>
        </table>
        <br>
        <table align="center" border="0" style="border: 1px solid #343a40;" cellpadding="5" cellspacing="0">
            <thead>
                <tr style="color: #f8f9fa; background-color: #343a40; border: 1px solid #f8f9fa;">
                    <th class="aj-auto">Nota</th>
                    <?php $__currentLoopData = $valores["dias"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="aj-auto"><?php echo e($dia); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <th class="aj-auto" style="text-align: center">Promedio</th>
                </tr>
            </thead>
            <tbody>
            <?php for($i=0; $i < count((array)$valores["resolutores_array"]); $i++): ?>
            <?php $__currentLoopData = $valores['dias']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($valores['dias'][0] == $dia): ?>
            <tr>
                    <td class="aj-auto" style="border: 1px solid #343a40; white-space: nowrap"><?php echo e($valores['resolutores_array'][$i]); ?></td>
            <?php endif; ?>
                    <?php $__currentLoopData = $valores['todo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $elemento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $valores['array_id_resolutor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor): ?>
                                <?php if($elemento->nota >= 1 and $elemento->nota <=3): ?>
                                <td style="text-align: center; border: 1px solid #343a40;">6</td>
                                <?php elseif($elemento->nota >=4 and $elemento->nota <=6): ?>
                                <td style="text-align: center; border: 1px solid #343a40;">4</td>
                                <?php elseif($elemento->nota >=7): ?>
                                <td style="text-align: center; border: 1px solid #343a40;">1</td>
                                <?php else: ?>
                                <td style="text-align: center; border: 1px solid #343a40;">10</td>
                                <?php endif; ?>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <td style="text-align: center; font-weight: bold; border: 1px solid #343a40;"><?php echo e($valores['totales_notas'][$i]); ?></td>
            </tr>
            <?php endfor; ?>
            </tbody>            
        </table>
    </div>
</div><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/emails/envia_reporte_semanal.blade.php ENDPATH**/ ?>