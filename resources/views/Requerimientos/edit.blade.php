@extends('Bases.dashboard')
@section('titulo', "Editar requerimiento")

@section('contenido')
    <h1>Editar requerimiento</h1>

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
    <div class="p-4">
        <div class="form-group col-md-8">
        <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}") }}">
            {{ method_field('PUT') }}
            {{ csrf_field() }}

            <label for="textoRequerimiento">Texto del requerimiento:</label>
            <br>
            <textarea class="form-control col-md-7" name="textoRequerimiento" value="{{ $requerimiento->textoRequerimiento }}" placeholder="Texto del requerimiento" rows="5" cols="50">{{ $requerimiento->textoRequerimiento }}</textarea>
            <br>
            <label for='fechaCierre'>Fecha de Solicitud:</label>
            <input value="{{ $solicitud }}" class="form-control col-md-3" type="date" name="fechaSolicitud">
            <br>              
            <label for='fechaCierre'>Fecha de Cierre:</label>
            <input value="{{ $cierre }}" class="form-control col-md-3" type="date" name="fechaCierre">
            <br>            
            <label for="idSolicitante">Solicitante:</label> 
            <br>       
            <select class="form-control col-md-3" name="idSolicitante">
                @foreach($solicitantes as $solicitante)
                    <optgroup>
                        <option value="{{$solicitante->id}}" @if($solicitanteEspecifico['0']->id == $solicitante->id){{ 'selected' }}@endif>{{ $solicitante->nombreSolicitante }}
                        </option>
                    </optgroup>
                @endforeach
            </select>
            <br>
            <label for="idPrioridad">Prioridad:</label>   
            <br>         
            <select class="form-control col-md-3" name="idPrioridad">
                @foreach($priorities as $priority)
                    <optgroup>
                        <option value="{{$priority->id}}" @if($prioridadEspecifica['0']->id == $priority->id){{'selected'}}@endif>{{ $priority->namePriority }}</option>
                    </optgroup>
                @endforeach
            </select>
            <br>
            <label for="idResolutor">Resolutor:</label>
            <br>        
            <select class="form-control col-md-3" name="idResolutor">
                @foreach($resolutors as $resolutor)
                    <optgroup>
                        <option value="{{$resolutor->id}}" @if($resolutorEspecifico['0']->id == $resolutor->id){{'selected'}}@endif>{{ $resolutor->nombreResolutor }}</option>
                    </optgroup>
                @endforeach
            </select>                      
            <br>
            <div id="creaAvance">
                <label for="textAvance">Ingresar avance al requerimiento:</label>
                <textarea class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>                    
            </div>  
            <br>                      
            <button class="btn btn-primary" type="submit">Actualizar requerimiento</button>
            <br>
        </form>

	<p>
        <br>
		<a href="{{url('requerimientos')}}">Volver al listado de requerimentos</a>
    </p>
@endsection    