<?php
if (isset($requerimientos)) {
	if (! empty($requerimientos)) {
		$pruebas = $requerimientos;
		$filename = "exportacion.xls";
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		$isPrintHeader = false;
		foreach ($pruebas as $row) {
			if (! $isPrintHeader) {
				echo implode("\t", array_keys($row)) . "\n";
				$isPrintHeader = true;
			}
			echo implode("\t", array_values($row)) . "\n";
		}
		exit();
	}
} else if (isset($texto)) {
		if (! empty($texto)) {
			$pruebas = $texto;
			$filename = "exportacion.doc";
			header("Content-Type: application/vnd.ms-word");
			header("Content-Disposition: attachment; filename=\"$filename\"");
			echo "<html>";
			echo "$pruebas";
			echo "</html>";
			exit();
		}
	}		
?>	

<?php $__env->startSection('titulo', "Extracciones"); ?>

<?php $__env->startSection('contenido'); ?>
<?php if(session()->has('msj')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo e(session('msj')); ?>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
<?php endif; ?>
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-table"></i> Exportar requerimientos</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Opciones para exportar</div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>                        
                        </div>
                </div>        
		<div class="ibox-body">	
			<div id="body" class="row">
				<div id="porEstado" class="from-row col-md-4">
					<h5>Por estado:</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/estado')); ?>">
						<select id="porEstado" class="form-control col-md-8" name="estado">
							<option selected="selected" disabled="disabled">Escoge una opción</option>
							<option value="2">Inactivo</option>
							<option value="1">Activo</option>
							<option value="3">Todos</option>
							<option value="4">Vencidos</option>
						</select>
						<br>
						<button id="btn1" class="btn btn-primary" type="submit">Extraer</button>	    	
					</form>
				</div>
				<div id="porEjecutado" class="from-row col-md-4">
					<h5>Por porcentaje ejecutado:</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/ejecutado')); ?>">
						<select id="comparacion" class="form-control col-md-7" name="comparacion">
							<option selected="selected" disabled="disabled">Escoger una opción</option>
							<option value="1">Menor o igual que</option>
							<option value="2">Mayor que</option>
						</select>
						<br>
						<div>
						<label for="porcentaje">Ingresa porcentaje de requerimiento(s):</label>
						<input class="form-control col-md-3" type="number" name="porcentaje">	
						</div>
						<br>
						<button class="btn btn-primary" type="submit">Extraer</button>	    	
					</form>
				</div>
				<div id="porCambios" class="from-row col-md-4">
					<h5>Por número de cambios:</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/cambios')); ?>">
						<select id="comparacion" class="form-control col-md-7" name="comparacion">
							<option selected="selected" disabled="disabled">Escoge una opción</option>
							<option value="1">Menor o igual que</option>
							<option value="2">Mayor que</option>
						</select>
						<br>
						<div>
						<label for="porcentaje">Ingresa n° de cambios:</label>
						<input class="form-control col-md-2" type="number" name="cambios">	
						</div>
						<br>
						<button class="btn btn-primary" type="submit">Extraer</button>	    	
					</form>
				</div>
			</div>
			<br><br>
			<div id="body2" class="row">
				<div id="porSolicitante" class="from-row col-md-4">
					<h5>Por solicitante:</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/solicitantes')); ?>">
						<select class="form-control col-md-8" name="idSolicitante">
							<option selected="selected" disabled="disabled">Escoge una opción</option>
							<?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value=<?php echo e($solicitante->id); ?>><?php echo e($solicitante->nombreSolicitante); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
						<br>
						<button class="btn btn-primary" type="submit">Extraer</button>
					</form>
				</div>
				<div id="porResolutor" class="from-row col-md-4">
					<h5>Por resolutor:</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/resolutores')); ?>">
						<select class="form-control col-md-8" name="idResolutor">
							<option selected="selected" disabled="disabled">Escoge una opción</option>
							<?php $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($resolutor->id); ?>"><?php echo e($resolutor->nombreResolutor); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
						<br>
						<button class="btn btn-primary" type="submit">Extraer</button>
					</form>
				</div>
				<?php if($user[0]->nombre!="resolutor"): ?>
				<div id="porTeam" class="from-row col-md-4">
					<h5>Por Equipo:</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/teams')); ?>">
						<select class="form-control col-md-8" name="idTeam">
							<option selected="selected" disabled="disabled">Escoge una opción</option>
							<?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value="<?php echo e($team->id); ?>"><?php echo e($team->nameTeam); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
						<br>
						<button class="btn btn-primary" type="submit">Extraer</button>
					</form>
				</div>
				<?php endif; ?>
			</div>
			<br>
			<?php if($user[0]->nombre!="resolutor"): ?>
			<div id="body3" class="row">
				<div id="word" class="from-row col-md-4">
					<h5>Exportar word (por solicitante)</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/word')); ?>">
						<select class="form-control col-md-8" name="solicitante">
							<option selected="selected" disabled="disabled">Escoge una opción</option>
							<?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<option value=<?php echo e($solicitante->id); ?>><?php echo e($solicitante->nombreSolicitante); ?></option>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
						</select>
						<br>						
						<button class="btn btn-primary" type="submit">Extraer</button>
					</form>
				</div>
				<div id="word" class="from-row col-md-4">
					<h5>Incidentes activos:</h5>
					<form method="GET" action="<?php echo e(url('/extracciones/incidentes')); ?>">					
						<button class="btn btn-primary" type="submit">Extraer</button>
					</form>
				</div>	                            
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
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
                        <th width=10%>&#193rea</th>
                        <th width=20%>Resolutor</th>
                        <th width=10%>Cantidad de RQ activos al <?php echo e($ayer); ?></th>
                        <th width=10%>Vencidos</th>
                        <th width=10%>Cantidad de RQ generados el d&#237a de hoy <?php echo e($hoy); ?></th>
                        <th width=10%>Cantidad de RQ cerrados el  d&#237a de hoy <?php echo e($hoy); ?></th>
                        <th width=10%>Cantidad de RQ activos al <?php echo e($hoy); ?></th>
                        <th width=10%>Color seg&#250n cant de RQ vencidos</th>
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
                                <td style="background-color: #45046a; font-weight: bold; color: white;">GRAVE</td>
                                <?php else: ?>
                                <td style="background-color: green"></td>
                                <?php endif; ?>                               
                            </tr>
                    <?php endfor; ?>
                    <tr>
                        <td colspan="2" style="font-weight: bold;">Total RQ</td>
                        <td style="font-weight: bold;"><?php echo e($valores['total_activos_ayer']); ?></td>
                        <td style="color: red; font-weight: bold;"><?php echo e($valores['total_vencidos']); ?></td>
                        <td style="font-weight: bold;"><?php echo e($valores['total_creados_hoy']); ?></td>
                        <td style="font-weight: bold;"><?php echo e($valores['total_cerrados_hoy']); ?></td>
                        <td style="font-weight: bold;"><?php echo e($valores['total_activos_hoy']); ?></td>
                    </tr>
                </tbody>
            </table>
        </table>
    </div>
</div>    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
	<script type="text/javascript">
		menu_activo('mExportar');
	</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Mantenedor\resources\views/Extraer/index.blade.php ENDPATH**/ ?>