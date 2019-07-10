@extends('Bases.base')
@section('titulo', "Terminar Requerimiento")

@section('contenido')
    <h1>Terminar requerimiento</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h6>Por favor corrige los errores debajo:</h6>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="p-4">
        <div class="form-group col-md-8">
        <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/guardar") }}">
            {{ method_field('PUT') }}
            {{ csrf_field() }}
            <label for="cierre">Cierre:</label>
            <br>
            <textarea class="form-control col-md-7" name="cierre"  placeholder="Texto del cierre" rows="5" cols="50"></textarea>
            <br>            
            <button class="btn btn-primary" type="submit">Terminar requerimiento</button>
            <br>
        </form>

	<p>
        <br>
		<a href="../">Volver al listado de requerimentos</a>
    </p>
@endsection    