@extends('Bases.dashboard')
@section('titulo', "Editar requerimiento")

@section('contenido')
@if ($errors->any())
    <div class="alert alert-danger">
        <h6>Por favor corrige los errores debajo:</h6>
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
        <li class="breadcrumb-item"><i class="fa fa-pencil"></i> Editar Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Registro de Requerimiento</div>
                </div>
                <div class="ibox-body">  
                    <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}") }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="col-sm-6 form-group">
                            <label for="textoRequerimiento">Texto del requerimiento:</label>
                            <textarea class="form-control col-md-12" name="textoRequerimiento" value="{{ $requerimiento->textoRequerimiento }}" placeholder="Texto del requerimiento" rows="5" cols="50">{{ $requerimiento->textoRequerimiento }}</textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for='fechaCierre'>Fecha de Solicitud:</label>
                            <input value="{{ $solicitud }}" class="form-control col-md-12" type="date" name="fechaSolicitud">
                        </div>
                        <div class="col-sm-6 form-group">         
                            <label for='fechaCierre'>Fecha de Cierre:</label>
                            <input value="{{ $cierre }}" class="form-control col-md-12" type="date" name="fechaCierre">
                        </div>
                        <div class="col-sm-6 form-group">       
                            <label for="idSolicitante">Solicitante:</label>   
                            <select class="form-control col-md-12" name="idSolicitante">
                                @foreach($solicitantes as $solicitante)
                                    <optgroup>
                                        <option value="{{$solicitante->id}}" @if($solicitanteEspecifico['0']->id == $solicitante->id){{ 'selected' }}@endif>{{ $solicitante->nombreSolicitante }}
                                        </option>
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="idPrioridad">Prioridad:</label>   
                            <select class="form-control col-md-12" name="idPrioridad">
                                @foreach($priorities as $priority)
                                    <optgroup>
                                        <option value="{{$priority->id}}" @if($prioridadEspecifica['0']->id == $priority->id){{'selected'}}@endif>{{ $priority->namePriority }}</option>
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="idResolutor">Resolutor:</label>    
                            <select class="form-control col-md-12" name="resolutor">
                                @foreach($resolutors as $resolutor)
                                    <optgroup>
                                        <option value="{{$resolutor->id}}" @if($resolutorEspecifico['0']->id == $resolutor->id){{'selected'}}@endif>{{ $resolutor->nombreResolutor }}</option>
                                    </optgroup>
                                @endforeach
                            </select>                     
                        </div>
                        <div class="col-sm-6 form-group">
                            <div id="creaAvance">
                                <label for="textAvance">Ingresar avance al requerimiento:</label>
                                <textarea class="form-control col-md-12" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>                    
                            </div>  
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Actualizar Registro</button>
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

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
    });
</script>
@endsection
