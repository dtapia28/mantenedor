@if(session()->has('msj'))
    <div class="alert alert-primary alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
@if(session()->has('msj2'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('msj2') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
@if(session()->has('msj3'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('msj3') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
@extends('Bases.dashboard')
@section('titulo', 'Modificar contraseña')
@section('contenido')
	<p>
		<h2>Cambiar contraseña</h2>
	</p>
	<br>
	<br>
	<div style="padding-left: 400px;" class="form-group col-md-12">
		<form method="POST" action="{{ url('user/change') }}">
			{{ csrf_field() }}
			<p>
				Contraseña actual: <input class="form-control col-md-4" type="password" name="oldPassword" id="oldPassword">
			</p>
			<p>
				Nueva contraseña: <input class="form-control col-md-4" type="password" name="newPassword" id="newPassword">
			</p>
			<p>
				Confirmar contraseña: <input class="form-control col-md-4" type="password" name="newPassword2" id="newPassword2">
			</p>
			<input type="hidden" value="{{ auth()->user()->id }}" name="usuario">
			<button class="btn btn-primary" type="submit">Modificar contraseña</button>
		</form>	
	</div>
@endsection