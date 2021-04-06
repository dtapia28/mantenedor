<?php $__env->startSection('titulo', "Actualizar requerimiento"); ?>
<?php $__env->startSection('contenido'); ?>
	<h1>Actualizar Requerimiento</h1>
	<br>
	<div class="p-4">
		<div class="form-group col-md-8">
		<form method='POST' action="<?php echo e(url("requerimientos/{$requerimiento->id}/save")); ?>">
			<?php echo e(method_field('PUT')); ?>

        	<?php echo e(csrf_field()); ?>

	        <label for='fechaCierre'>Fecha real de Cierre (no obligatoria):</label>
	        <input class="form-control col-md-3" value="<?php echo e(old('fechaRealCierre', $requerimiento->fechaRealCierre)); ?>" type="date" name="fechaRealCierre">
	        <br>
	        <label for="porcentajeEjecutado">Porcentaje ejecutado:</label>
	        <input class="form-control col-md-2" value="<?php echo e(old('porcentajeEjecutado', $requerimiento->porcentajeEjecutado)); ?>" type="number" name="porcentajeEjecutado">
	        <br>	
	        <label for="cierre">Cierre (no obligatorio):</label>
	        <br>
	        <textarea class="form-control col-md-7" name="cierre" placeholder="Cierre" rows="5" cols="50"></textarea>
	        <br>
            <button class="btn btn-primary" type="submit">Actualizar</button>
		</form>
		</div>
	</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Bases.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>