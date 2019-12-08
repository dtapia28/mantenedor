@extends('Bases.dashboard')
@section('titulo', "Editar Avance")

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
<h1 class="page-title"><i class="fa fa-address-card"></i> Editar Avance Requerimiento {{ $requerimiento->id }}</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-arrow-circle-right"></i> Editar Avance</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Editar Registro de Avance</div>
                </div>
                <div class="ibox-body">
                    <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/avances/{$avance->id}") }}">
                        {{ method_field('PUT') }}           
                        {{ csrf_field() }}
                        <div class="col-sm-6 form-group">
                            <label class="" for="textAvance">Texto del avance</label>
                            <textarea class="form-control col-md-12" name="textAvance" placeholder="Texto del avance" rows="5" cols="50">{{ $avance->textAvance }}</textarea>
                            <input type="hidden" id="idAvance" name="idAvance" value="{{$avance->id}}">               
                        </div>              
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Actualizar Registro</button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ url('requerimientos/'.$requerimiento->id) }}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar al requerimiento</a>
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
