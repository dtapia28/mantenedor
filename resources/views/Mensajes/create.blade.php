@extends('Bases.dashboard')
@section('titulo', "Nuevo Mensaje")

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
    <h1 class="page-title"><i class="fa fa-comments-o"></i> Mensajes</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-plus"></i> Nuevo Mensaje</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Enviar nuevo mensaje</div>
                </div>
                <div class="ibox-body">    
                    <form method="POST" action="{{ url('mensajes/store') }}">
                        {{ csrf_field() }}
                        <div class="form-group row">
                            <label for="nameDe" class="col-md-2 text-md-right">De</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" name="nombre" id="name" value="{{ auth()->user()->name }}" readonly>
                                <input type="hidden" name="nombreDe" id="nameDe" value="{{ auth()->user()->id }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nombrePara" class="col-md-2 text-md-right">Para</label>
                            <div class="col-md-4">            
                            <select class="form-control" name="nombrePara" id="nombrePara">
                                <option value="-1">Seleccione...</option>
                                @foreach($users as $item)
                                    <option value={{ $item->id }}>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="asunto" class="col-md-2 text-md-right">Asunto</label>
                            <div class="col-md-4">
                                <input class="form-control" type="text" name="asunto" id="asunto" value="{{ old('asunto') }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="mensaje" class="col-md-2 text-md-right">Mensaje</label>
                            <div class="col-md-4">
                                <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="10">{{ old('mensaje') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-4 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Enviar Mensaje</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="{{url('mensajes')}}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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
        menu_activo('mMensajes');
    });
</script>
@endsection