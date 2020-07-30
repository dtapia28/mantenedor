@extends('Bases.dashboard')
@section('titulo', "Editar Tarea")

@section('contenido')
<div class="page-heading">
<h1 class="page-title"><i class="fa fa-address-card"></i> Editar Tarea Requerimiento {{$tarea->id2}}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-pencil"></i> Editar Tarea</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Registro de Tarea</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/tareas/{$tarea->id}") }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha de Solicitud</label>
                            <input value="{{ $solicitud }}" class="form-control col-md-12" type="date" name="fechaSolicitud">
                        </div>
                        <div class="col-sm-6 form-group">       
                            <label for='fechaCierre'>Fecha de Cierre</label>
                            <input value="{{ $cierre }}" class="form-control col-md-12" type="date" name="fechaCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label class="" for="textoRequerimiento">T�tulo Tarea</label>
                            <textarea id="titulo_tarea" class="form-control col-md-12" name="titulo_tarea" placeholder="T�tulo Tarea" rows="1" cols="50">{{ $tarea->titulo_tarea }}</textarea>
                        </div>                         
                        <div class="col-sm-6 form-group">
                            <label for="texto">Tarea:</label>
                            <textarea class="form-control col-md-12" name="texto" placeholder="Tarea" rows="5" cols="50">{{ $tarea->textoTarea }}</textarea>
                            <input type="hidden" name="tarea" value={{$tarea->id}}>
                            <input type="hidden" name="req" value={{$requerimiento->id}}>        
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Actualizar Registro</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ url('requerimientos/'.$requerimiento->id) }}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar al requerimiento</a>
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
        menu_activo('mRequerimientos');
    });
</script>
@endsection
