@if(session()->has('msj'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
@extends('Bases.dashboard')
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

                <label class="" for="textoRequerimiento">Solicitud:</label>
                <textarea id="texto" class="form-control col-md-7" name="textoRequerimiento" placeholder="Solicitud" rows="5" cols="50"></textarea>
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
                <a href="{{ url('/solicitantes/nuevo') }}?volver=1">Crear Solicitante</a>
                <br>
                <div id="recargar">
                <label for='idResolutor'>Resolutor:</label>        
                <br>                 
                <select class='form-control col-md-3' name='idResolutor'>
                    @foreach($resolutors as $resolutor)
                        <optgroup>
                            <option value={{$resolutor->id}}>{{$resolutor->nombreResolutor}}</option>
                        </optgroup>
                    @endforeach
                </select>
                <a onclick="window.open(this.href, this.target, 'width=300,height=400'); return false;" href='{{ url('/resolutors/nuevo') }}?volver=1'>Crear Resolutor</a>                    
                </div>
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
                <a href="{{ url('/priorities/nueva') }}?volver=1">Crear Prioridad</a>

                <br>
                <br>
                <button class="btn btn-primary" type="submit">Crear Requerimiento</button>        
            </form>
        </div>
    </div>    
    <br>
    <p>
        <a href="../requerimientos">Regresar al listado de requerimientos</a>
    </p>
@endsection