<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
<div class="ibox" style="background-color: #E7EDF5;">
    <div style=" opacity:0.3; padding: 10px; text-align: center;">
        <a href="https://app.kinchika.com/login"><img src="<?php echo e(asset('img/logo-blue.png')); ?>" alt="logo"></a>
    </div>
    <div class="ibox-head">
        <div class="ibox-title"><h1>Reporte Requerimientos</h1></div>       
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
                text-align: center;
            }
            
            tbody th{
                border: 1px solid black;
                text-align: left;
            }
            table,td {
                border: 1px solid black;
            }           
        </style>
        <table width=80%>
            <tr >
                <td class="head_table"><h2>Requerimientos al <?php echo e($hoy); ?></h2></td>
            </tr>
            <table border="2" width=80%>
                <thead>
                    <tr>
                        <th width=10%>Area</th>
                        <th width=20%>Resolutor</th>
                        <th width=10%>Cantidad de RQ activos al <?php echo e($ayer); ?></th>
                        <th width=10%>Vencidos</th>
                        <th width=10%>Cantidad de RQ generado el dia de hoy <?php echo e($hoy); ?></th>
                        <th width=10%>Cantidad de RQ cerrado el  dia de hoy <?php echo e($hoy); ?></th>
                        <th width=10%>Cantidad de RQ activos al <?php echo e($hoy); ?></th>
                        <th width=10%>Color segun cant de RQ vencidos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0; $i<count((array)$valores["resolutores_array"]); $i++): ?>
                            <tr>
                                <td><?php echo e($valores['equipos_array'][$i]); ?></td>
                                <td><?php echo e($valores['resolutores_array'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['pendientes_resolutor_hoy'][$i]+$valores['cerrados'][$i]-$valores['creadoHoy_resolutor'][$i]); ?></td>
                                <?php if($valores['vencidos'][$i] >= 1): ?>
                                <td style="text-align: center; color: red; font-weight: bold;"><?php echo e($valores['vencidos'][$i]); ?></td>
                                <?php else: ?>
                                <td style="text-align: center;"><?php echo e($valores['vencidos'][$i]); ?></td>
                                <?php endif; ?>
                                <td style="text-align: center;"><?php echo e($valores['creadoHoy_resolutor'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['cerrados'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['pendientes_resolutor_hoy'][$i]); ?></td>
                                <?php if($valores['vencidos'][$i] >= 1 and $valores['vencidos'][$i]<=3): ?>
                                <td style="background-color: yellow"></td>
                                <?php elseif($valores['vencidos'][$i]>=4 and $valores['vencidos'][$i]<=6): ?>
                                <td style="background-color: red"></td>
                                <?php elseif($valores['vencidos'][$i]>=7): ?>
                                <td style="background-color: #45046a; font-weight: bold; color: white; text-align: center;">GRAVE</td>
                                <?php else: ?>
                                <td style="background-color: green"></td>
                                <?php endif; ?>                               
                            </tr>
                    <?php endfor; ?>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Total RQ</td>
                        <td style="font-weight: bold; text-align: center;"><?php echo e($valores['total_activos_ayer']); ?></td>
                        <td style="color: red; font-weight: bold; text-align: center;"><?php echo e($valores['total_vencidos']); ?></td>
                        <td style="font-weight: bold; text-align: center;"><?php echo e($valores['total_creados_hoy']); ?></td>
                        <td style="font-weight: bold; text-align: center;"><?php echo e($valores['total_cerrados_hoy']); ?></td>
                        <td style="font-weight: bold; text-align: center;"><?php echo e($valores['total_activos_hoy']); ?></td>
                    </tr>
                </tbody>
            </table>
        </table>
        <br>
        <table border='1'>
            <thead>
                <tr>
                    <th>Semaforo</th>
                    <?php $__currentLoopData = $valores["dias"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e($dia); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            </thead>
            <tbody>
            <?php for($i=0; $i < count((array)$valores["resolutores_array"]); $i++): ?>
            <?php $__currentLoopData = $valores['dias']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($valores['dias'][0] == $dia): ?>
            <tr>
                    <td><?php echo e($valores['resolutores_array'][$i]); ?></td>
            <?php endif; ?>
                    <?php $__currentLoopData = $valores['todo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $elemento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $valores['array_id_resolutor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor): ?>
                                <?php if($elemento->nota >= 1 and $elemento->nota <=3): ?>
                                <td style="background-color: yellow"></td>
                                <?php elseif($elemento->nota >=4 and $elemento->nota <=6): ?>
                                <td style="background-color: red"></td>
                                <?php elseif($elemento->nota >=7): ?>
                                <td style="background-color: #45046a; font-weight: bold; color: white; text-align: center;">GRAVE</td>
                                <?php else: ?>
                                <td style="background-color: green"></td>
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
        <table border='1'>
            <thead>
                <tr>
                    <th>Nota</th>
                    <?php $__currentLoopData = $valores["dias"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e($dia); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <th style="text-align: center">Promedio</th>
                </tr>
            </thead>
            <tbody>
            <?php for($i=0; $i < count((array)$valores["resolutores_array"]); $i++): ?>
            <?php $__currentLoopData = $valores['dias']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dia): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($valores['dias'][0] == $dia): ?>
            <tr>
                    <td><?php echo e($valores['resolutores_array'][$i]); ?></td>
            <?php endif; ?>
                    <?php $__currentLoopData = $valores['todo']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $elemento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $valores['array_id_resolutor']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $res): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($elemento->fecha == $dia and $valores['resolutores_array'][$i] == $res[0]->nombreResolutor and $res[0]->id == $elemento->id_resolutor): ?>
                                <?php if($elemento->nota >= 1 and $elemento->nota <=3): ?>
                                <td style="text-align: center">6</td>
                                <?php elseif($elemento->nota >=4 and $elemento->nota <=6): ?>
                                <td style="text-align: center">4</td>
                                <?php elseif($elemento->nota >=7): ?>
                                <td style="text-align: center">1</td>
                                <?php else: ?>
                                <td style="text-align: center">10</td>
                                <?php endif; ?>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <td style="text-align: center; font-weight: bold;"><?php echo e($valores['totales_notas'][$i]); ?></td>
            </tr>
            <?php endfor; ?>
            </tbody>            
        </table>
    </div>
</div>

<?php /**PATH /home1/kinchika/public_html/laravel/resources/views/emails/envia_reporte_semanal.blade.php ENDPATH**/ ?>