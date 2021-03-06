@extends('Bases.dashboard')
@section('titulo', "Crear Requerimiento")

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
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Requerimiento</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url('requerimientos/crear') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-sm-6 form-group">
                            <label for='idTipo'>Tipo</label>               
                            <select class="form-control col-md-12" name="idTipo" id="idTipo">
                                <option value=1>Requerimiento</option>
                                <option value=2>Incidente</option>
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="" for="textoRequerimiento">Solicitud</label>
                            <textarea id="texto" class="form-control col-md-12" name="textoRequerimiento" placeholder="Solicitud" rows="5" cols="50"></textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaEmail'>Fecha original de requerimiento</label>
                            <input class="form-control col-md-12" type="date" name="fechaEmail" id="fechaEmail">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaSolicitud'>Fecha inicio de seguimiento</label>
                            <input class="form-control col-md-12" type="date" name="fechaSolicitud" id="fechaSolicitud">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha solicitada de cierre</label>
                            <input class="form-control col-md-12" type="date" name="fechaCierre" id="fechaCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='idGestor'>Gestor</label>               
                            <select class="form-control col-md-12" name="idGestor" id="idGestor">
                                @foreach($gestores as $gestor)
                                        <option value={{ $gestor->id }}>{{ $gestor->nombreResolutor }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($user[0]->nombre != "solicitante")
                        <div class="col-sm-6 form-group">
                            <label for="idSolicitante">Solicitante</label>
                            <select class="form-control col-md-12" name="idSolicitante" id="idSolicitante">
                                @foreach($solicitantes as $solicitante)
                                        <option value={{ $solicitante->id }}>{{ $solicitante->nombreSolicitante }}</option>
                                @endforeach
                            </select>
                            @if($user[0]->nombre=="administrador")
                            <a href="{{ url('/users/nuevo') }}?volver=1" onclick="storeCacheForm()">Crear Solicitante</a>
                            @endif
                        </div>
                        @endif
                        <div class="col-sm-6 form-group">
                            <label for="team">Equipo Resolutores</label>
                            <select class="form-control col-md-12" id="team" name="team">
                                <option value="">Seleccione un Equipo</option>                    
                                @foreach($teams as $team)
                                        <option value={{ $team->id }}>{{ $team->nameTeam }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='idResolutor'>Resolutor</label>                      
                            <select class='form-control col-md-12' id="resolutor" name='idResolutor'>
                            </select>
                            @if($user[0]->nombre=="administrador")
                            <a href='{{ url('/users/nuevo') }}?volver=1' onclick="storeCacheForm()">Crear Resolutor</a>
                            @endif
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="idPrioridad">Prioridad</label>        
                            <select class="form-control col-md-12" name="idPrioridad" id="idPrioridad">
                                @foreach($priorities as $priority)
                                        <option value={{ $priority->id }}>{{ $priority->namePriority }}</option>
                                @endforeach
                            </select>
                            @if($user[0]->nombre=="administrador")
                            <a href="{{ url('/priorities/nueva') }}?volver=1" onclick="storeCacheForm()">Crear Prioridad</a>
                            @endif
                        </div>
                        <div class="col-sm-6 form-group">
                            <div id="creaComentario">
                                <label for="textComentario">Ingresar comentario para resolutor</label>
                                <textarea class="form-control col-md-12" name="comentario" placeholder="Comentario para resolutor" rows="5" cols="50" id="comentario"></textarea>                     
                            </div>
                        </div>
                        <div class="col-sm-6 form-group">
                            <div id="creaAvance">
                                <label for="textAvance">Ingresar avance al requerimiento</label>
                                <textarea class="form-control col-md-12" name="textAvance" id="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>                    
                            </div>
                        </div>
                        <div class="col-md-6 form-group">
                            <div id="creaAdjuntos">
                                <label for="textAvance">Adjuntar archivo al requerimiento</label>
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
                                    <button type="submit" onclick="storeCacheForm()" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle" id="submitBtn"></i> Guardar Registro</button>
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
<script src="{{ asset('vendor/dropzone/dropzone.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#team').on('change', function(){
            var id_team = $(this).val();
            $.get('../requerimientos/script', {id_team: id_team}, function(resolutors){
                $('#resolutor').empty();
                $('#resolutor').append("<option value=''>Selecciona un resolutor</opcion>");
                $.each(resolutors, function(index, value){
                    $('#resolutor').append("<option value='"+index+"'>"+value+"</opcion>");
                });
            });
        });
        
        menu_activo('mRequerimientos');

        let textoReq       = sessionStorage.getItem('stRequerimiento'),
            fechaEmail     = sessionStorage.getItem('stFechaEmail'),
            fechaSolicitud = sessionStorage.getItem('stFechaSolicitud'),
            fechaCierre    = sessionStorage.getItem('stFechaCierre'),
            idGestor       = sessionStorage.getItem('stIdGestor'),
            idSolicitante  = sessionStorage.getItem('stIdSolicitante'),
            team           = sessionStorage.getItem('stTeam'),
            idResolutor    = sessionStorage.getItem('stIdResolutor'),
            idPrioridad    = sessionStorage.getItem('stIdPrioridad'),
            comentario     = sessionStorage.getItem('stComentario'),
            textAvance     = sessionStorage.getItem('stTextAvance'),
            tipo           = sessionStorage.getItem('stTipo');

        (textoReq!="" && textoReq!=null) ? $('#texto').val(textoReq) : $('#texto').val("");
        (fechaEmail!="" && fechaEmail!=null) ? $('#fechaEmail').val(fechaEmail) : $('#fechaEmail').val("");
        (fechaSolicitud!="" && fechaSolicitud!=null) ? $('#fechaSolicitud').val(fechaSolicitud) : $('#fechaSolicitud').val("");
        (fechaCierre!="" && fechaCierre!=null) ? $('#fechaCierre').val(fechaCierre) : $('#fechaCierre').val("");
        (idGestor!="" && idGestor!=null) ? $('#idGestor').val(idGestor) : $('#idGestor').val("");
        (idSolicitante!="" && idSolicitante!=null) ? $('#idSolicitante').val(idSolicitante) : $('#idSolicitante').val("");
        (team!="" && team!=null) ? $('#team').val(team) : $('#team').val("");
        (tipo!="" && tipo!=null) ? $('#tipo').val(tipo) : $('#tipo').val("");
        if (team!="" && team!=null) {
            $.get('../requerimientos/script', {id_team: team}, function(resolutors){
                $('#resolutor').empty();
                $('#resolutor').append("<option value=''>Selecciona un resolutor</opcion>");
                $.each(resolutors, function(index, value){
                    $('#resolutor').append("<option value='"+index+"'>"+value+"</opcion>");
                });
            });
        }
        (idResolutor!="" && idResolutor!=null) ? $('#resolutor').val(idResolutor) : $('#resolutor').val("");
        (idPrioridad!="" && idPrioridad!=null) ? $('#idPrioridad').val(idPrioridad) : $('#idPrioridad').val("");
        (comentario!="" && comentario!=null) ? $('#comentario').val(comentario) : $('#comentario').val("");
        (textAvance!="" && textAvance!=null) ? $('#textAvance').val(textAvance) : $('#textAvance').val("");
    });

    function storeCacheForm() {
        if (typeof(Storage) !== 'undefined') {
            sessionStorage.setItem('stTipo', $('#tipo').val());
            sessionStorage.setItem('stRequerimiento', $('#texto').val());
            sessionStorage.setItem('stFechaEmail', $('#fechaEmail').val());
            sessionStorage.setItem('stFechaSolicitud', $('#fechaSolicitud').val());
            sessionStorage.setItem('stFechaCierre', $('#fechaCierre').val());
            sessionStorage.setItem('stIdGestor', $('#idGestor').val());
            sessionStorage.setItem('stIdSolicitante', $('#idSolicitante').val());
            sessionStorage.setItem('stTeam', $('#team').val());
            sessionStorage.setItem('stIdResolutor', $('#resolutor').val());
            sessionStorage.setItem('stIdPrioridad', $('#idPrioridad').val());
            sessionStorage.setItem('stComentario', $('#comentario').val());
            sessionStorage.setItem('stTextAvance', $('#textAvance').val());
        }
    }

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
			alert("Archivo con extensi�n no v�lida\nSu archivo: " + nombre_archivo);
			borrar_req();
			return false;
		}
		if(fileSize > 5000000){
			alert("Archivo con tama�o no v�lido\nSu archivo: " + nombre_archivo);
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
