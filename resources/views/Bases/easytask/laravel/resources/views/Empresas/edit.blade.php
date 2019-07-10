@extends('Bases.base')
@section('titulo', "Editar Empresa")

@section('contenido')
    <h1>Editar Empresa</h1>
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
            <form method="POST" action="{{ url("empresas/{$empresa->id}") }}">
                {{ method_field('PUT') }}
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input class="form-control col-md-5" type="text" name="nombreEmpresa" id="nombreEmpresa" value="{{ old('nombreEmpresa', $empresa->nombreEmpresa) }}">
                <br>
                <button class="btn btn-primary" type="submit">Actualizar empresa</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="../../empresas">Regresar al listado de empresas</a>
    </p>
@endsection