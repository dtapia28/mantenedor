@extends('Bases.dashboard')
@section('titulo', "Editar avance")
@section('contenido')
    <h1>Editar avance</h1>
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
            <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/avances/{$avance->id}") }}">
            	{{ method_field('PUT') }}           
                {{ csrf_field() }}
                 <input type="hidden" id="idAvance" name="idAvance" value="{{$avance->id}}">               
                <label class="" for="textAvance">Texto del avance:</label>
                <br>
                <textarea class="form-control col-md-10" name="textAvance" placeholder="Texto del avance" rows="5" cols="50"></textarea>
            	<label>Texto del avance: {{ $avance->textAvance }}</label>                
                <br>
                <button class="btn btn-primary" type="submit">Actualizar avance</button>
            </form>
         </div>
    </div>        
    <p>
        <a href="/requerimientos/{{$requerimiento->id}}">Regresar al requerimiento</a>
    </p>
@endsection