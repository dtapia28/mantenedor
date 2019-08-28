@extends('Bases.dashboard')
@section('titulo', "Crear Prioridad")

@section('contenido')
    <h1>Crear Prioridad</h1>
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
            <form method="POST" action="{{ url('priorities/crear') }}">
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="namePriority" id="name" value="{{ old('namePriority') }}">
                <input type="hidden" name="volver" value="{{  $volver }}">                
                <br>
                <button class="btn btn-primary" type="submit">Crear Prioridad</button>
            </form>
        </div>
    </div>        
    <p>
        <a href="{{url('priorities')}}">Regresar al listado de prioridades</a>
    </p>
@endsection