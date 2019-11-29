@extends('Bases.dashboard')
@section('titulo', "Crear usuario")
@section('contenido')
                <div class="card-body">
                    <form method="POST" action="{{ url('users/guardar') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nombre') }}</label>

                            <div class="col-md-3">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="apellido" class="col-md-4 col-form-label text-md-right">{{ __('Apellido') }}</label>

                            <div class="col-md-3">
                                <input id="apellido" type="text" class="form-control @error('apellido') is-invalid @enderror" name="apellido" value="{{ old('apellido') }}" required autocomplete="apellido" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>                        

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-3">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idRole" class="col-md-4 col-form-label text-md-right">Role</label>
                            <div class="col-md-5">
                                <select id="idRole" class='form-control col-md-7' name='idRole'>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nombre }}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idRole" id="lTeam" class="col-md-4 col-form-label text-md-right"style='display: none';>Equipo</label>
                            <div class="col-md-5">
                                <select style='display: none' id="idTeam" class='form-control col-md-7' name='idTeam'></select>
                            </div>
                        </div>                                               
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Registrar') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#idRole').on('change', function(){
            var combo = document.getElementById('idRole');
            var selected = combo.options[combo.selectedIndex].text;
            if(selected == 'resolutor' || selected == 'gestor'){
                document.getElementById('lTeam').style.display = 'block';
                document.getElementById('idTeam').style.display = 'block';
                $.get('/users/script', function(teams){
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
            }
        });
    });    
</script>                
@endsection