@extends('Bases.dashboard')
@section('titulo', "Parametros del sistema")

@section('contenido')
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-user-circle"></i> Usuarios</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-edit"></i> Editar Parámetros</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Parámetros del Sistema</div>
                </div>
                <div class="ibox-body"> 
					<form method="POST" action="{{ url('user/parametros/guardar') }}">
						{{ csrf_field() }}
						<div class="form-group row">
							<label for="supervisor" class="col-md-2 text-md-right">Email de supervisor</label>
							<div class="col-md-4">
								<input value="{{ $email }}" class="form-control" type="text" name="supervisor" placeholder="example@example.cl">
							</div>
						</div>
						<div class="form-group row">
							<label for="color" class="col-md-2 text-md-right">Color del sistema</label>
							<div class="col-md-4">
								<select class="form-control" name="color">
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
							</div>
						</div>
						<div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-5">
                                </div>
                            </div>
                        </div>
					</form>
				</div>
			</div>
        </div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        menu_activo('mUsuarios');
    });
</script>
@endsection
