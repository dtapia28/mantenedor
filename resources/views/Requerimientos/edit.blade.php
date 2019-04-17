@extends('Bases.base')
@section('titulo', "Editar empresa")

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
            <textarea class="form-control col-md-10" name="textoRequerimiento" value='{{ old('textoRequerimiento', $requerimiento->textoRequerimiento) }}' placeholder="Texto del requerimiento" rows="5" cols="50"></textarea>
            <label>Texto de requerimiento: {{ $requerimiento->textoRequerimiento }}</label>
            <br>
            <label for="idSolicitante">Solicitante:</label> 
            <br>       
            <select class="form-control col-md-5" name="idSolicitante">
                @foreach($solicitantes as $solicitante)
                    <optgroup>
                        <option value="{{ old('idSolicitante', $solicitante->id) }}">{{ $solicitante->nombreSolicitante }}</option>
                    </optgroup>
                @endforeach
            </select>
            <br>
            <label for="idPrioridad">Prioridad:</label>   
            <br>         
            <select class="form-control col-md-5" name="idPrioridad">
                @foreach($priorities as $priority)
                    <optgroup>
                        <option value="{{ old('idPrioridad', $priority->id) }}">{{ $priority->namePriority }}</option>
                    </optgroup>
                @endforeach
            </select>
            <br>
            <label for="idResolutor">Resolutor:</label>
            <br>        
            <select class="form-control col-md-5" name="idResolutor">
                @foreach($resolutors as $resolutor)
                    <optgroup>
                        <option value="{{ old('idResolutor', $resolutor->id) }}">{{ $resolutor->nombreResolutor }}</option>
                    </optgroup>
                @endforeach
            </select>                      
            <br>
            <label for="idEmpresa">Empresa:</label>
            <br>             
            <select class="form-control col-md-5" name="idEmpresa">
                @foreach($empresas as $empresa)
                    <optgroup>
                        <option value="{{ old('idEmpresa', $empresa->id) }}">{{ $empresa->nombreEmpresa }}</option>
                    </optgroup>
                @endforeach
            </select>
            <br>            
            <button class="btn btn-primary" type="submit">Actualizar requerimiento</button>
            <br>
        </form>

	<p>
        <br>
		<a href="/requerimientos/">Volver al listado de requerimentos</a>
    </p>
@endsection    