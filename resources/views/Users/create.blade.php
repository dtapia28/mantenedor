@extends('Bases.dashboard')
@section('titulo', "Crear usuario")

@section('contenido')
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-user-circle"></i> Usuarios</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Usuario</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Usuario</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url('users/guardar') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-2 col-form-label text-md-right">{{ __('Nombre') }}</label>
            
                            <div class="col-md-4">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="apellido" class="col-md-2 col-form-label text-md-right">{{ __('Apellido') }}</label>
            
                            <div class="col-md-4">
                                <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido" autofocus>
            
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                        
            
                        <div class="form-group row">
                            <label for="email" class="col-md-2 col-form-label text-md-right">{{ __('E-Mail') }}</label>
            
                            <div class="col-md-4">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idRole" class="col-md-2 col-form-label text-md-right">Rol</label>
                            <div class="col-md-4">
                                <select id="idRole" class='form-control col-md-12' name='idRole'>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idRole" id="lTeam" class="col-md-2 col-form-label text-md-right" style='display: none'>Equipo</label>
                            <div class="col-md-4">
                                <select style='display: none' id="idTeam" class='form-control' name='idTeam'></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idLider" id="lLider" class="col-md-2 form-check-label text-md-right" style='display: none'>Lider de Equipo</label>
                            <div class="col-md-4">
                                <input type="checkbox" class='align-items-center pull-left' name='idLider' id='idLider' style='display: none'>
                            </div>
                        </div>                                            
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer">
                                    <i class="fa fa-check-circle"></i> Guardar Registro
                                </button>
                                </div>
                                <div class="col-md-5">
                                <a href="{{url('users')}}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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
        $('#idRole').on('change', function(){
            var combo = document.getElementById('idRole');
            var selected = combo.options[combo.selectedIndex].text;
            if(selected == 'resolutor' || selected == 'gestor'){
                document.getElementById('lTeam').style.display = 'block';
                document.getElementById('idTeam').style.display = 'block';
                document.getElementById('idLider').style.display = 'block';
                document.getElementById('lLider').style.display = 'block';
                $.get('../users/script', function(teams){
                    $('#idTeam').empty();
                    $('#idTeam').append("<option value=''>Seleccione un equipo</option>");
                    $.each(teams, function(index, value){
                        $('#idTeam').append("<option value='"+index+"'>"+value+"</option>");
                    });
                });
            } else 
            {
            document.getElementById('idTeam').style.display = 'none';
            document.getElementById('lTeam').style.display = 'none';
            document.getElementById('idLider').style.display = 'none';
            document.getElementById('lLider').style.display = 'none';
            }
        });
        menu_activo('mUsuarios');
    });    
</script>                
@endsection
