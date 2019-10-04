@extends('Bases.dashboard')
@section('titulo', "Crear Resolutor")

@section('contenido')
<h1>Crear Resolutor</h1>

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
        <form method="POST" action="{{ url('resolutors/crear') }}">
            {{ csrf_field() }}

            <label for="name">Nombre:</label>
            <input class="form-control col-md-7" type="text" name="nombreResolutor" id="name" value="{{ old('nombreResolutor') }}">
            <br>
            <label for="idTeam">Equipo:</label>                
            <select class="form-control col-md-6" name="idTeam">
                @foreach($teams as $team)
                <optgroup>
                    <option value={{ $team->id }}>{{ $team->nameTeam }}</option>
                </optgroup>
                @endforeach
            </select>
            <br>
            <input type="hidden" name="volver" value="{{  $volver }}">                
            <input type="checkbox" name="lider" value=1> Lider de Equipo
            <br>        
            <button class="btn btn-primary" type="submit">Crear Resolutor</button>
        </form>
    </div>
</div>    
<p>
    <a href="{{url('resolutors')}}">Regresar al listado de resolutores</a>
</p>
@endsection