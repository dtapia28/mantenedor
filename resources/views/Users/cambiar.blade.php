@extends('Bases.dashboard')
@section('titulo', 'Modificar contraseña')

@section('contenido')
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
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-user-circle"></i> Usuarios</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-edit"></i> Cambiar Contraseña</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Cambiar Contraseña del Usuario</div>
                </div>
                <div class="ibox-body"> 
					<form method="POST" action="{{ url('user/change') }}">
						{{ csrf_field() }}
						<div class="form-group row">
							<label for="actual" class="col-md-2 text-md-right">Contraseña actual</label>
							<input class="form-control col-md-4" type="password" name="oldPassword" id="oldPassword">
						</div>
						<div class="form-group row">
							<label for="nueva" class="col-md-2 text-md-right">Nueva contraseña</label>
							<input class="form-control col-md-4" type="password" name="newPassword" id="newPassword">
						</div>
						<div class="form-group row">
							<label for="confirma" class="col-md-2 text-md-right">Confirmar contraseña</label>
							<input class="form-control col-md-4" type="password" name="newPassword2" id="newPassword2">
							<input type="hidden" value="{{ auth()->user()->id }}" name="usuario">
						</div>
						<div class="form-group row">
							<div class="col-md-2"></div>
							<div class="col-md-4 form-inline">
								<div class="col-md-8">
									<button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Modificar Contraseña</button>
								</div>
								<div class="col-md-4">
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
