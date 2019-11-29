@extends('Bases.dashboard')
@section('titulo', "Tarea en requerimiento")

@section('contenido')
    <h1>Crear tarea para requerimiento {{$requerimiento->id2}}</h1>
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
            <form method="POST" action="{{ url('tareas/ingresar') }}">                
                {{ csrf_field() }}
                <label for='fechaSolicitud'>Fecha de Solicitud:</label>
                <input class="form-control col-md-3" type="date" name="fechaSolicitud">
                <br>
                <label for='fechaCierre'>Fecha de Cierre:</label>
                <input class="form-control col-md-3" type="date" name="fechaCierre"> 
                <br>
                <label for="team">Equipo Resolutores:</label>
                <br>
                <select class="form-control col-md-4" id="team" name="team">
                    <option value="">Seleccione un Equipo</option>                    
                    @foreach($teams as $team)
                            <option value={{ $team->id }}>{{ $team->nameTeam }}</option>
                    @endforeach
                </select>
                <label for='idResolutor'>Resolutor:</label>        
                <br>                 
                <select class='form-control col-md-3' id="resolutor" name='idResolutor'>
                </select>                               
                <label class="" for="textoRequerimiento">Tarea:</label>
                <textarea id="texto" class="form-control col-md-7" name="texto" placeholder="Tarea" rows="5" cols="50"></textarea>
                <input type="hidden" id="idRequerimiento" name="idRequerimiento" value="{{$requerimiento->id}}">  
                <br>                                       
                <button class="btn btn-primary" type="submit">Guardar</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="{{ url('/requerimientos/'.$requerimiento->id) }}">Regresar al requerimiento</a>
    </p>
@endsection
@section('script2')
<script type="text/javascript">
    $(document).ready(function(){
        $('#team').on('change', function(){
            var id_team = $(this).val();
            $.get('/requerimientos/script', {id_team: id_team}, function(resolutors){
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