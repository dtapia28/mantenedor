@extends('Bases.base')
@section('titulo', "Crear Requerimiento")

@section('contenido')
    <h1>Crear Requerimiento</h1>

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

    <form method="POST" action="{{ url('requerimientos/crear') }}">
        {{ csrf_field() }}

        <label for="textoRequerimiento">Texto del requerimiento:</label>
        <br>
        <textarea name="textoRequerimiento" placeholder="Texto del requerimiento" rows="5" cols="50"></textarea>>
        <br>
        <label for='fechaEmail'>Fecha de Email:</label>
        <input type="date" name="fechaEmail">
        <br>
        <label for='fechaSolicitud'>Fecha de Solicitud:</label>
        <input type="date" name="fechaSolicitud">
        <br>
        <label for='fechaCierre'>Fecha de Cierre:</label>
        <input type="date" name="fechaCierre">
        <br>
        <label for='fechaCierre'>Fecha real de Cierre (no obligatoria):</label>
        <input type="date" name="fechaRealCierre">
        <br>
        <label for="numeroCambios">Número de cambios:</label>
        <input type="number" name="numeroCambios">
        <br>
        <label for="porcentajeEjecutado">% Ejecutado:</label>
        <input type="number" name="porcentajeEjecutado">
        <br>
        <label for="cierre">Cierre (no obligatorio):</label>
        <br>
        <textarea name="cierre" placeholder="Cierre" rows="5" cols="50"></textarea>>
        <br> 
        <br>
        <label for="idSolicitante">Solicitante:</label>
        <br>
        <select name="idSolicitante">
            @foreach($solicitantes as $solicitante)
                <optgroup>
                    <option value={{ $solicitante->id }}>{{ $solicitante->nombreSolicitante }}</option>
                </optgroup>
            @endforeach
        </select>
        <br>
        <label for="idEmpresa">Empresa:</label>        
        <br>                 
        <select name="idEmpresa">
            @foreach($empresas as $empresa)
                <optgroup>
                    <option value={{ $empresa->id }}>{{ $empresa->nombreEmpresa }}</option>
                </optgroup>
            @endforeach
        </select>
        <br>
        <label for="idResolutor">Resolutor:</label>        
        <br>                 
        <select name="idResolutor">
            @foreach($resolutors as $resolutor)
                <optgroup>
                    <option value={{ $resolutor->id }}>{{ $resolutor->nombreResolutor }}</option>
                </optgroup>
            @endforeach
        </select>
        <br>
        <label for="idPrioridad">Prioridad:</label>        
        <br>                 
        <select name="idPrioridad">
            @foreach($priorities as $priority)
                <optgroup>
                    <option value={{ $priority->id }}>{{ $priority->namePriority }}</option>
                </optgroup>
            @endforeach
        </select>             
        <br>
        <br>
        <button class="btn btn-primary" type="submit">Crear Requerimiento</button>        
    </form>
    <br>
    <p>
        <a href="/requerimientos">Regresar al listado de requerimientos</a>
    </p>
@endsection