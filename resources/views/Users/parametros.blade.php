@extends('Bases.dashboard')
@section('titulo', "Parametros del sistema ")
@section('contenido')
<header>
	<h1>Parametros del sistema</h1>
</header>
<br>
<div class="form-row align-items-center">
	<div class="form-group col-md-8">
		<form method="POST" action="{{ url('user/parametros/guardar') }}">
			{{ csrf_field() }}
			<label for="supervisor">Email de supervisor:</label>
			<input value="{{ $email }}" class="form-control col-md-5" type="text" name="supervisor" placeholder="example@example.cl">
			<br>
			<label for="color">Color del sistema:</label>
			<select class="form-control col-md-3" name="color">
				<optgroup>
					<option value="1" @if ($color == 1)
						{{ 'selected' }}
					@endif>Rojo</option>
					<option value="2" @if ($color == 2)
						{{ 'selected' }}
					@endif>Azul</option>
					<option value="3" @if ($color == 3)
						{{ 'selected' }}
					@endif>Verde</option>
					<option value="4" @if ($color == 4)
						{{ 'selected' }}
					@endif>Amarillo</option>
				</optgroup>
			</select>
			<br>
			<button class="btn btn-primary" type="submit">Guardar</button>
		</form>
	</div>
</div>
@endsection