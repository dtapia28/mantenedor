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
                <label for='fechaEmail'>Fecha original de requerimiento:</label>
                <input class="form-control col-md-3" type="date" name="fechaEmail">
                <br>
                <label for='fechaSolicitud'>Fecha inicio de seguimiento:</label>
                <input class="form-control col-md-3" type="date" name="fechaSolicitud">
                <br>
                <label for='fechaCierre'>Fecha solicitada de cierre:</label>
                <input class="form-control col-md-3" type="date" name="fechaCierre">
                <br>
                <label for='idGestor'>Gestor:</label>        
                <br>                 
                <select class="form-control col-md-3" name="idGestor">
                    @foreach($gestores as $gestor)
                            <option value={{ $gestor->id }}>{{ $gestor->nombreResolutor }}</option>
                    @endforeach
                </select>
                <br>              
                @if($user[0]->nombre != "solicitante")
                <label for="idSolicitante">Solicitante:</label>
                <br>
                <select class="form-control col-md-3" name="idSolicitante">
                    @foreach($solicitantes as $solicitante)
                            <option value={{ $solicitante->id }}>{{ $solicitante->nombreSolicitante }}</option>
                    @endforeach
                </select>
                <a href="{{ url('/users/nuevo') }}?volver=1">Crear Solicitante</a>
                <br>
                <br>
                @endif
                <label for="team">Equipo Resolutores:</label>
                <br>
                <select class="form-control col-md-4" id="team" name="team">
                    <option value="">Seleccione un Equipo</option>                    
                    @foreach($teams as $team)
                            <option value={{ $team->id }}>{{ $team->nameTeam }}</option>
                    @endforeach
                </select>
                <br>
                <label for='idResolutor'>Resolutor:</label>        
                <br>                 
                <select class='form-control col-md-3' id="resolutor" name='idResolutor'>
                </select>
                <a href='{{ url('/users/nuevo') }}?volver=1'>Crear Resolutor</a>                    
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
                <div id="creaComentario">
                    <label for="textComentario">Ingresar comentario para resolutor:</label>
                   <textarea class="form-control col-md-10" name="comentario" placeholder="Comentario para resolutor" rows="5" cols="50"></textarea>                     
                </div>
                <br>
                <div id="creaAvance">
                    <label for="textAvance">Ingresar avance al requerimiento:</label>
                   <textarea class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>                    
                </div>
                <br>
                <button class="btn btn-primary" type="submit">Crear Requerimiento</button>        
            </form>
        </div>
    </div>    
    <br>
    <p>
        <a href="{{ url('requerimientos') }}">Regresar al listado de requerimientos</a>
    </p>
@endsection
@section('script2')
<script type="text/javascript">
    $(document).ready(function(){
        $('#team').on('change', function(){
            var id_team = $(this).val();
            $.get('../requerimientos/script', {id_team: id_team}, function(resolutors){
                $('#resolutor').empty();
                $('#resolutor').append("<option value=''>Selecciona un resolutor</opcion>");
                $.each(resolutors, function(index, value){
                    $('#resolutor').append("<option value='"+index+"'>"+value+"</opcion>");
                });
            });
        });
    });
</script>
@endsection