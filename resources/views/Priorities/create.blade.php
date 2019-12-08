@extends('Bases.dashboard')
@section('titulo', "Crear Prioridad")

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
    <h1 class="page-title"><i class="fa fa-sort-amount-desc"></i> Prioridades</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-arrow-circle-right"></i> Crear Prioridad</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Nuevo Registro de Prioridad</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url('priorities/crear') }}">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="name" class="col-md-2 text-md-right">Nombre</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" name="namePriority" id="name" value="{{ old('namePriority') }}">
                                <input type="hidden" name="volver" value="{{  $volver }}">
                            </div>
                        </div>             
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Guardar Registro</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="{{url('priorities')}}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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
