@extends('Bases.dashboard')
@section('titulo', "Terminar Requerimiento")

@section('contenido')
@if(session()->has('msj'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <h6>Por favor corrige los errores debajo:</h6>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-check"></i> Terminar Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Terminar Solicitud de Requerimiento</div>
                </div>
                <div class="ibox-body"> 
                    <form method="POST" action="{{ url("requerimientos/{$requerimiento->id}/guardar") }}">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="col-sm-6 form-group">
                            <label for="cierre">Cierre</label>
                            <textarea class="form-control col-md-12" name="cierre"  placeholder="Texto del cierre" rows="5" cols="50"></textarea>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label style="display: none" for='fechaCierre'>Fecha Real de Cierre (no obligatoria)</label>
                            <input style="display: none" class="form-control col-md-12" value="{{ old('fecha', $fecha->format("yy-m-d")) }}" type="date" name="fechaRealCierre">
                        </div>
                        <div class="col-sm-6 form-group">
                            <div class="col-md-12 form-inline">
                                <div class="col-md-7">
                                    <button type="submit" class="btn btn-success btn-block mb-2 mr-sm-2 mb-sm-0" style="cursor:pointer"><i class="fa fa-check-circle"></i> Terminar requerimiento</button>
                                </div>
                                <div class="col-md-5">
                                    <a href="{{url('requerimientos')}}" class="btn btn-outline-primary btn-block"><i class="fa fa-arrow-left"></i> Regresar</a>
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
        menu_activo('mRequerimientos');
    });
</script>
@endsection
