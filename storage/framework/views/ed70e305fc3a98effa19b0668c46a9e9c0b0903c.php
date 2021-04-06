<?php $__env->startSection('titulo', "Extracciones"); ?>
<?php $__env->startSection('contenido'); ?>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/index_style.css')); ?>">
<h1 style="padding-bottom: 40px" align="center">Exportar requerimientos</h1>
<div id="body" class="row">
<div id="porEstado" class="from-row col-md-4">
	<h5>Por estado:</h5>
	<form method="GET" action="<?php echo e(url('../public/extracciones/estado')); ?>">
		<select id="porEstado" class="form-control col-md-8" name="estado">
			<option selected="selected" disabled="disabled">Escoge una opción</option>
			<option value="0">Inactivo</option>
			<option value="1">Activo</option>
	    </select>	
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
	    <div>
	    <label for="porcentaje">Ingresa porcentaje de requerimiento(s):</label>
	    <input class="form-control col-md-3" type="number" name="porcentaje">	
	    </div>
		<button class="btn btn-primary" type="submit">Extraer</button>	    	
	</form>
</div>
<div id="porEjecutado" class="from-row col-md-4">
	<h5>Por número de cambios:</h5>
	<form method="GET" action="<?php echo e(url('/extracciones/cambios')); ?>">
		<select id="comparacion" class="form-control col-md-7" name="comparacion">
			<option selected="selected" disabled="disabled">Escoge una opción</option>
			<option value="1">Menor o igual que</option>
			<option value="2">Mayor que</option>
	    </select>
	    <div>
	    <label for="porcentaje">Ingresa n° de cambios:</label>
	    <input class="form-control col-md-2" type="number" name="cambios">	
	    </div>
		<button class="btn btn-primary" type="submit">Extraer</button>	    	
	</form>
</div>
</div>
<div id="body2" class="row">
	<div id="porSolicitante" class="from-row col-md-4">
		<h5>Por solicitante:</h5>
		<form method="GET" action="<?php echo e(url('/extracciones/solicitantes')); ?>">
        	<select class="form-control col-md-8" name="idSolicitante">
				<option selected="selected" disabled="disabled">Escoge una opción</option>
            	<?php $__currentLoopData = $solicitantes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $solicitante): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                	<optgroup>
                    	<option value=<?php echo e($solicitante->id); ?>><?php echo e($solicitante->nombreSolicitante); ?></option>
                    </optgroup>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
			<button class="btn btn-primary" type="submit">Extraer</button>            			
		</form>
	</div>
	<div id="porResolutor" class="from-row col-md-4">
		<h5>Por resolutor:</h5>
		<form method="GET" action="<?php echo e(url('/extracciones/resolutores')); ?>">
			<select class="form-control col-md-8" name="idResolutor">
				<option selected="selected" disabled="disabled">Escoge una opción</option>
				<?php $__currentLoopData = $resolutors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolutor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
					<optgroup>
						<option value="<?php echo e($resolutor->id); ?>"><?php echo e($resolutor->nombreResolutor); ?></option>
					</optgroup>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
			</select>
			<button class="btn btn-primary" type="submit">Extraer</button>
		</form>
	</div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/itconsul/public_html/easytask/laravel/resources/views/Extraer/index.blade.php ENDPATH**/ ?>