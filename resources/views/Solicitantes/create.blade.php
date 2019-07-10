@extends('Bases.dashboard')
@section('titulo', "Crear Solicitante")

@section('contenido')
    <h1>Crear Solicitante</h1>
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
            <form method="POST" action="{{ url('solicitantes/crear') }}">
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input class="form-control col-md-7" type="text" name="nombreSolicitante" id="name" value="{{ old('nombreSolicitante') }}">
                <br>
                <button class="btn btn-primary" type="submit">Crear Solicitante</button>
            </form>
        </div>
    </div>        
    <p>
        <a href="/solicitantes">Regresar al listado de solicitantes</a>
    </p>
@endsection