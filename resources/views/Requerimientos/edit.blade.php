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

    <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}") }}">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <label for="textoRequerimiento">Texto del requerimiento:</label>
        <br>
        <textarea name="textoRequerimiento" value='{{ old('textoRequerimiento', $requerimiento->textoRequerimiento) }}' placeholder="Texto del requerimiento" rows="5" cols="50"></textarea>>
        <br>
        <label for="idSolicitante">Solicitante:</label> 
        <br>       
        <select name="idSolicitante">
            @foreach($solicitantes as $solicitante)
                <optgroup>
                    <option value="{{ old('idSolicitante', $solicitante->id) }}">{{ $solicitante->nombreSolicitante }}</option>
                </optgroup>
            @endforeach
        </select>
        <br>
        <label for="idPrioridad">Prioridad:</label>   
        <br>         
        <select name="idPrioridad">
            @foreach($priorities as $priority)
                <optgroup>
                    <option value="{{ old('idPrioridad', $priority->id) }}">{{ $priority->namePriority }}</option>
                </optgroup>
            @endforeach
        </select>
        <br>
        <label for="idResolutor">Resolutor:</label>
        <br>        
        <select name="idResolutor">
            @foreach($resolutors as $resolutor)
                <optgroup>
                    <option value="{{ old('idResolutor', $resolutor->id) }}">{{ $resolutor->nombreResolutor }}</option>
                </optgroup>
            @endforeach
        </select>                      
        <br>
        <label for="idEmpresa">Empresa:</label>
        <br>             
        <select name="idEmpresa">
            @foreach($empresas as $empresa)
                <optgroup>
                    <option value="{{ old('idEmpresa', $empresa->id) }}">{{ $empresa->nombreEmpresa }}</option>
                </optgroup>
            @endforeach
        </select>
        <br> 
        <br>            
        <button type="submit">Actualizar requerimiento</button>
    </form>

	<p>
		<a href="/requerimientos/">Volver al listado de requerimentos</a>
    </p>