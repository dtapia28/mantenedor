@extends('Bases.base')
@section('titulo', "Crear Requerimiento")

@section('contenido')
    <header>
    <h1>Crear Requerimiento</h1>
    </header>
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
            <form method="POST" action="{{ url('requerimientos/crear') }}">
                {{ csrf_field() }}

                <label class="" for="textoRequerimiento">Texto del requerimiento:</label>
                <br>
                <textarea class="form-control col-md-7" name="textoRequerimiento" placeholder="Texto del requerimiento" rows="5" cols="50"></textarea>
                <br>
                <label for='fechaEmail'>Fecha de Email:</label>
                <input class="form-control col-md-3" type="date" name="fechaEmail">
                <br>
                <label for='fechaSolicitud'>Fecha de Solicitud:</label>
                <input class="form-control col-md-3" type="date" name="fechaSolicitud">
                <br>
                <label for='fechaCierre'>Fecha de Cierre:</label>
                <input class="form-control col-md-3" type="date" name="fechaCierre">
                <br>
                <label for="idSolicitante">Solicitante:</label>
                <br>
                <select class="form-control col-md-3" name="idSolicitante">
                    @foreach($solicitantes as $solicitante)
                        <optgroup>
                            <option value={{ $solicitante->id }}>{{ $solicitante->nombreSolicitante }}</option>
                        </optgroup>
                    @endforeach
                </select>
                <br>
                <label for="idEmpresa">Empresa:</label>        
                <br>                 
                <select class="form-control col-md-3" name="idEmpresa">
                    @foreach($empresas as $empresa)
                        <optgroup>
                            <option value={{ $empresa->id }}>{{ $empresa->nombreEmpresa }}</option>
                        </optgroup>
                    @endforeach
                </select>
                <br>
                <label for="idResolutor">Resolutor:</label>        
                <br>                 
                <select class="form-control col-md-3" name="idResolutor">
                    @foreach($resolutors as $resolutor)
                        <optgroup>
                            <option value={{ $resolutor->id }}>{{ $resolutor->nombreResolutor }}</option>
                        </optgroup>
                    @endforeach
                </select>
                <br>
                <label for="idPrioridad">Prioridad:</label>        
                <br>                 
                <select class="form-control col-md-3" name="idPrioridad">
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
        </div>
    </div>    
    <br>
    <p>
        <a href="/requerimientos">Regresar al listado de requerimientos</a>
    </p>
@endsection