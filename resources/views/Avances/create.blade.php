@extends('Bases.dashboard')
@section('titulo', "Ingresar Avance")

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
    <h1 class="page-title"><i class="fa fa-address-card"></i> Avance Requerimiento {{ $requerimiento->id2 }}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Avance</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Avance</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url('avances/ingresar') }}">                
                        {{ csrf_field() }}
                        <div class="col-sm-6 form-group">
                            <input type="hidden" id="idRequerimiento" name="idRequerimiento" value="{{$requerimiento->id}}">
                            <label for='fechaCierre'>Fecha real de Cierre (no obligatoria)</label>
                            <input class="form-control col-md-12" value="{{ old('fechaRealCierre', $requerimiento->fechaRealCierre) }}" type="date" name="fechaRealCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="porcentajeEjecutado">Porcentaje ejecutado</label>
                            <input class="form-control col-md-12" value="{{ old('porcentajeEjecutado', $requerimiento->porcentajeEjecutado) }}" type="number" name="porcentajeEjecutado">
                        </div>
                        <div class="col-sm-6 form-group">    
                            <label class="" for="textAvance">Texto del avance</label>
                            <textarea class="form-control col-md-12" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
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
<<<<<<< HEAD
    @endif
    <div class="form-row align-items-center">
        <div class="form-group col-md-8">    
            <form method="POST" action="{{ url('avances/ingresar') }}">                
                {{ csrf_field() }}
                 <input type="hidden" id="idRequerimiento" name="idRequerimiento" value="{{$requerimiento->id}}">
                <label for='fechaCierre'>Fecha real de Cierre (no obligatoria):</label>
                <input class="form-control col-md-3" value="{{ old('fechaRealCierre', $requerimiento->fechaRealCierre) }}" type="date" name="fechaRealCierre">
                <br>
                <label for="porcentajeEjecutado">Porcentaje ejecutado:</label>
                <input class="form-control col-md-2" value="{{ old('porcentajeEjecutado', $requerimiento->porcentajeEjecutado) }}" type="number" name="porcentajeEjecutado">
                <br>                 
                <label class="" for="textAvance">Texto del avance:</label>
                <br>
                <textarea minlength="20" class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
                <br>
                <button class="btn btn-primary" type="submit">Guardar avance</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="{{ url('requerimientos/'.$requerimiento->id) }}">Regresar al requerimiento</a>
    </p>
@endsection
=======
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mRequerimientos');
    });
</script>
@endsection
>>>>>>> aea5e24a011df9482809d70b96b8afeb29d0ee72
