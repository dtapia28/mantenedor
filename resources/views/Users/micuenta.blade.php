@extends('Bases.dashboard')
@section('titulo', "Mi cuenta")

@section('contenido')
@if (session('message'))
	<div class="alert alert-{{ session('message')['alert'] }}">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
		{{ session('message')['text'] }}
	</div>
	@php session()->forget('message'); @endphp
@endif
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-user-circle"></i> Usuario</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-edit"></i> Mi cuenta</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar datos de mi cuenta</div>
                </div>
                <div class="ibox-body"> 
					<form method="POST" action="{{ url('user/account/guardar') }}" enctype="multipart/form-data">
						{{ csrf_field() }}
						<div class="form-group row">
							<label for="supervisor" class="col-md-2 text-md-right">Nombre</label>
							<div class="col-md-5">
								<input value="{{ $nombre }}" class="form-control" type="text" name="nombre" id="nombre" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label for="supervisor" class="col-md-2 text-md-right">Email</label>
							<div class="col-md-5">
								<input value="{{ $email }}" class="form-control" type="text" name="email" id="email" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label for="color" class="col-md-2 text-md-right">Foto de Perfil</label>
							<div class="col-md-5">
								<input type='file' name="archivo" id='archivo' onchange="validar_archivo(this.id)" title="Seleccionar archivo" /><br>
								<small class="text-dark">Resoluciones recomendadas: <strong>64x64, 128x128, 256x256</strong></small><br>
								<small class="text-dark">Extesiones permitidas: <strong>jpg, jpeg, png</strong></small><br>
								<small class="text-dark">Tamaño máximo: <strong>1 MB</strong></small>
								<div id="valor" style="font-size: 11px"><!-- fix --></div>
								<div class="limpiar"><!-- fix --></div>
							</div>
						</div>
						<br>
						<div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-5 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-6">
									<button type="button" class="btn btn-danger btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer" onclick="eliminarFotoPerfil()"><i class="fa fa-check-circle"></i> Eliminar Foto Actual</button>
                                </div>
                            </div>
                        </div>
					</form>
				</div>
			</div>
        </div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mUsuarios');
	});
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-Token': $('input[name="_token"]').val()
		}
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
	function eliminarFotoPerfil() {
		let mensaje = '¿Confirma eliminar la foto de perfil actual?';
		if (confirm(mensaje))
        {
			$.ajax({
				type: 'post',
				url: 'account/eliminarfoto',
				dataType: 'json',
				success: function (data) {
					window.location.replace('');
				},
				error: function (data) {
					console.log('Error:', data);
					alert("Error al eliminar la foto de perfil");
				}
			});
		}
	}
</script>
@endsection

