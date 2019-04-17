@extends('Bases.base')
@section('titulo', "Ingresar Avance")

@section('contenido')
    <h1>Crear avance para requerimiento n° {{$requerimiento->id}}</h1>
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
            <form method="POST" action="{{ url('avance/ingresar') }}">
                {{ csrf_field() }}
                <label class="" for="textAvance">Texto del avance:</label>
                <br>
                <textarea class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
                <br>
                <button class="btn btn-primary" type="submit">Crear empresa</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="/empresas">Regresar al listado de empresas</a>
    </p>
@endsection