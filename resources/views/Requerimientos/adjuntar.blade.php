@extends('Bases.dashboard')
@section('titulo', "Adjuntar Archivo")

@section('css')
    <link href="{{ asset('vendor/dropzone/dropzone.css') }}" rel="stylesheet">
@endsection

@section('contenido')
@if(session()->has('msj'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
@if ($errors->any())
<div class="alert alert-danger">
    <h6>Por favor corrige los siguientes errores:</h6>
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-paperclip"></i> Adjuntar Archivo</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Adjuntar Archivo a Requerimiento</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url('requerimientos/adjuntar') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-6 form-group">
                            <table class="table table-condensed">
                                <tr>
                                    <td width="40%"><strong>Id Requerimiento/Tarea</strong></td>
                                    <td width="60%">
                                        {{ $requerimiento->id2 }}
                                        <input type="hidden" name="id_req" id="id_req" value="{{ $requerimiento->id }}">
                                        <input type="hidden" name="id_req2" id="id_req2" value="{{ $requerimiento->id2 }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Descripción Requerimiento/Tarea</strong></td>
                                    <td>{{ $requerimiento->textoRequerimiento }}</td>
                                </tr>
                                <tr><td></td><td></td></tr>
                            </table>
                        </div>
                        <div class="col-md-6 form-group">
                            <div id="creaAdjuntos">
                                <label for="textAvance">Seleccione el Archivo a Adjuntar</label>
                                <div class="dropzone" id="myDropzone">
                                    <div class="fallback">
                                        <input id="archivo" name="archivo" type="file" title="Seleccionar archivo" onchange="validar_archivo_req(this.id)"><br>
                                        <small class="text-dark">Extesiones permitidas: <strong>jpg, jpeg, png, pdf, docx, xlsx, pptx</strong></small><br>
                                        <small class="text-dark">Tamaño máximo: <strong>5 MB</strong></small>
                                        <div id="valor" style="font-size: 11px"><!-- fix --></div>
                                        <div class="limpiar"><!-- fix --></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 pt-4 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle" id="submitBtn"></i> Guardar Archivo</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="{{url('requerimientos')}}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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

@section('script2')
<script type="text/javascript">
    $(document).ready(function() {
        menu_activo('mRequerimientos');
    }); 
    function validar_archivo_req(id_archivo) {
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
		var ext_validas = /\.(jpg|jpeg|png|pdf|doc|docx|xls|xlsx|csv|ppt|pptx)$/i.test(nombre_archivo);
		
		if (!ext_validas){
			alert("Archivo con extensión no válida\nSu archivo: " + nombre_archivo);
			borrar_req();
			return false;
		}
		if(fileSize > 5000000){
			alert("Archivo con tamaño no válido\nSu archivo: " + nombre_archivo);
			borrar_req();
			return false;
		}
		document.getElementById('valor').innerHTML = "Archivo seleccionado: <b>" + nombre_archivo + "<\/b>";       
	}
    function borrar_req() {
		document.getElementById('valor').innerHTML = "";
		var vacio = document.getElementById('archivo').value = "";
		return true
	}
</script>
@endsection