@extends('Bases.dashboard')
@section('titulo', "Tarea en Requerimiento")

@section('contenido')
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
    <h1 class="page-title"><i class="fa fa-address-card"></i> Crear tarea para Requerimiento {{$requerimiento->id2}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Tarea</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Tarea</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url('tareas/ingresar') }}">                
                        {{ csrf_field() }}
                        <div class="col-sm-6 form-group">
                            <label for='fechaSolicitud'>Fecha de Solicitud</label>
                            <input class="form-control col-md-12" type="date" name="fechaSolicitud">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha de Cierre</label>
                            <input class="form-control col-md-12" type="date" name="fechaCierre"> 
                        </div>
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
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="" for="textoRequerimiento">Título Tarea</label>
                            <textarea id="titulo" class="form-control col-md-12" name="titulo" placeholder="Título Tarea" rows="1" cols="50"></textarea>
                        </div>                        
                        <div class="col-sm-6 form-group">
                            <label class="" for="textoRequerimiento">Tarea</label>
                            <textarea id="texto" class="form-control col-md-12" name="texto" placeholder="Tarea" rows="5" cols="50"></textarea>
                            <input type="hidden" id="idRequerimiento" name="idRequerimiento" value="{{$requerimiento->id}}">
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ url('/requerimientos/'.$requerimiento->id) }}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar al requerimiento</a>
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
    $(document).ready(function(){
        $('#team').on('change', function(){
            var id_team = $(this).val();
            $.get('../../../requerimientos/script', {id_team: id_team}, function(resolutors){
                $('#resolutor').empty();
                $('#resolutor').append("<option value=''>Selecciona un resolutor</opcion>");
                $.each(resolutors, function(index, value){
                    $('#resolutor').append("<option value='"+index+"'>"+value+"</opcion>");
                });
            });
        });
        menu_activo('mRequerimientos');
    });
</script>
@endsection
