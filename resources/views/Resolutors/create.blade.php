@extends('Bases.dashboard')
@section('titulo', "Crear Resolutor")

@section('contenido')
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
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-address-book-o"></i> Resolutores</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Crear Resolutor</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Resolutor</div>
                </div>
                <div class="ibox-body">    
                    <form method="POST" action="{{ url('resolutors/crear') }}">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="name" class="col-md-2 text-md-right">Nombre</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" name="nombreResolutor" id="name" value="{{ old('nombreResolutor') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="idTeam" class="col-md-2 text-md-right">Equipo</label>
                            <div class="col-md-4">            
                            <select class="form-control" name="idTeam">
                                @foreach($teams as $team)
                                    <optgroup>
                                        <option value={{ $team->id }}>{{ $team->nameTeam }}</option>
                                    </optgroup>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="lider" class="col-md-2 text-md-right">Líder de Equipo</label>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="ui-checkbox">
                                        <input type="checkbox" name="lider" class="form-control form-check-input" value=1>
                                        <span class="input-span"></span>
                                    </label>
                                    <input type="hidden" name="volver" value="{{  $volver }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="{{url('resolutors')}}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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
        menu_activo('mResolutores');
    });
</script>
@endsection
