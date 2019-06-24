@extends('Bases.base')
@section('titulo', "Editar Avance")

@section('contenido')
    <h1>Editar avance</h1>
    <br>
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
    <div class="form-row align-items-center">
        <div class="form-group col-md-8">    
            <form method="POST" action="{{ url('/requerimientos/{requerimiento}/avances') }}">
           		{{ method_field('PUT') }}                            
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
                <textarea class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
           		 <label>Texto de requerimiento: {{ $avance->textAvance }}</label>
           		 <br>
                <button class="btn btn-primary" type="submit">Actualizar avance</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="/requerimientos/{{$requerimiento->id}}">Regresar al requerimiento</a>
    </p>
@endsection