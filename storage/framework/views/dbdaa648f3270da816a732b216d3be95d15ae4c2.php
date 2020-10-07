<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Reporte RQ</div>
	<div class="ibox-tools">
            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
	</div>        
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
        </style>
        <table width=80%>
            <tr >
                <td class="head_table">Requerimientos al <?php echo e($hoy); ?></td>
            </tr>
            <table width=80%>
                <thead>
                    <tr>
                        <th width=10%>Área</th>
                        <th width=20%>Resolutor</th>
                        <th width=10%>Cantidad de RQ pendientes al <?php echo e($ayer); ?></th>
                        <th width=10%>Vencidos</th>
                        <th width=10%>Cantidad de RQ generado el día de hoy <?php echo e($hoy); ?></th>
                        <th width=10%>Cantidad de RQ cerrado el  día de hoy <?php echo e($hoy); ?></th>
                        <th width=10%>Cantidad de RQ Pendientes al <?php echo e($hoy); ?></th>
                        <th width=10%>Semáforo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for($i=0; $i<count((array)$valores["resolutores_array"]); $i++): ?>
                            <tr>
                                <td><?php echo e($valores['equipos_array'][$i]); ?></td>
                                <td><?php echo e($valores['resolutores_array'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['pendientes_resolutor'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['vencidos'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['creadoHoy_resolutor'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['cerrados'][$i]); ?></td>
                                <td style="text-align: center;"><?php echo e($valores['pendientes_resolutor_hoy'][$i]); ?></td>
                                <?php if($valores['vencidos'][$i] >= 1 and $valores['vencidos'][$i]<=3): ?>
                                <td style="background-color: yellow"></td>
                                <?php elseif($valores['vencidos'][$i]>=4 and $valores['vencidos'][$i]<=6): ?>
                                <td style="background-color: red"></td>
                                <?php elseif($valores['vencidos'][$i]>7): ?>
                                <td style="background-color: purple">GRAVE</td>
                                <?php else: ?>
                                <td style="background-color: green"></td>
                                <?php endif; ?>                             
                            </tr>
                    <?php endfor; ?>        
                </tbody>
            </table>
        </table>    
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/emails/envia_reporte_semanal.blade.php ENDPATH**/ ?>