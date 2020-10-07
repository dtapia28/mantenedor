<?php $__env->startSection('titulo', "Parametros del sistema"); ?>

<?php $__env->startSection('contenido'); ?>
<?php if(session('message')): ?>
	<div class="alert alert-<?php echo e(session('message')['alert']); ?>">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		<?php echo e(session('message')['text']); ?>

	</div>
	<?php session()->forget('message'); ?>
<?php endif; ?>
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-user-circle"></i> Usuarios</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-edit"></i> Editar Parámetros</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Parámetros del Sistema</div>
                </div>
                <div class="ibox-body"> 
					<form method="POST" action="<?php echo e(url('user/parametros/guardar')); ?>" enctype="multipart/form-data">
						<?php echo e(csrf_field()); ?>

						<div class="form-group row">
							<label for="supervisor" class="col-md-2 text-md-right">Email de supervisor</label>
							<div class="col-md-4">
								<input value="<?php echo e($email); ?>" class="form-control" type="text" name="supervisor" placeholder="example@example.cl">
							</div>
						</div>
						<div class="form-group row">
							<label for="color" class="col-md-2 text-md-right">Color del sistema</label>
							<div class="col-md-4">
								<select class="form-control" name="color">
									<optgroup>
										<option value="1" <?php if($color == 1): ?>
											<?php echo e('selected'); ?>

										<?php endif; ?>>Rojo</option>
										<option value="2" <?php if($color == 2): ?>
											<?php echo e('selected'); ?>

										<?php endif; ?>>Azul</option>
										<option value="3" <?php if($color == 3): ?>
											<?php echo e('selected'); ?>

										<?php endif; ?>>Verde</option>
										<option value="4" <?php if($color == 4): ?>
											<?php echo e('selected'); ?>

										<?php endif; ?>>Amarillo</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="color" class="col-md-2 text-md-right">Logo de la Empresa</label>
							<div class="col-md-4">
								<input type='file' name="archivo" id='archivo' onchange="validar_archivo(this.id)" title="Seleccionar archivo" /><br>
								<small class="text-dark">Resoluciones recomendadas: <strong>128x128, 256x256, 512x512</strong></small><br>
								<small class="text-dark">Extesiones permitidas: <strong>jpg, jpeg, png</strong></small><br>
								<small class="text-dark">Tamaño máximo: <strong>1 MB</strong></small>
								<div id="valor" style="font-size: 11px"><!-- fix --></div>
								<div class="limpiar"><!-- fix --></div>
							</div>
						</div>
						<br>
						<div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-5">
                                </div>
                            </div>
                        </div>
					</form>
				</div>
			</div>
        </div>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mUsuarios');
    });

	function validar_archivo(id_archivo) {
		var archivo = document.getElementById(id_archivo).value;

		var uploadedFile = document.getElementById(id_archivo);
		var fileSize = uploadedFile.files[0].size;      

		if(navigator.userAgent.indexOf('Linux') != -1){
		var SO = "Linux"; }
		else if((navigator.userAgent.indexOf('Win') != -1) &&(navigator.userAgent.indexOf('95') != -1)){
		var SO = "Win"; }
		else if((navigator.userAgent.indexOf('Win') != -1) &&(navigator.userAgent.indexOf('NT') != -1)){
		var SO = "Win"; }
		else if(navigator.userAgent.indexOf('Win') != -1){
		var SO = "Win"; }
		else if(navigator.userAgent.indexOf('Mac') != -1){
		var SO = "Mac"; }
		else { var SO = "no definido";
		}

		if (SO = "Win") {
			var arr_ruta = archivo.split("\\");
		} else {
			var arr_ruta = archivo.split("/");
		}

		var nombre_archivo = (arr_ruta[arr_ruta.length-1]);
		var ext_validas = /\.(jpg|jpeg|png)$/i.test(nombre_archivo);
		
		if (!ext_validas){
			alert("Archivo con extensión no válida\nSu archivo: " + nombre_archivo);
			borrar();
			return false;
		}

		if(fileSize > 1000000){
			alert("Archivo con tamaño no válido\nSu archivo: " + nombre_archivo);
			borrar();
			return false;
		}

		document.getElementById('valor').innerHTML = "Archivo seleccionado: <b>" + nombre_archivo + "<\/b>";       
	}

	function borrar() {
		document.getElementById('valor').innerHTML = "";
		var vacio = document.getElementById('archivo').value = "";
		return true
	}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Bases.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home1/kinchika/public_html/laravel/resources/views/Users/parametros.blade.php ENDPATH**/ ?>