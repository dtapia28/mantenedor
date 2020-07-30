@extends('Bases.dashboard')
@section('titulo', "Log de requerimiento")
@section('contenido')
<div class="page-heading">
    <h1 class="page-title"><i class="fa fa-address-card"></i> Requerimientos</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-file-text-o"></i> Log del Requerimiento</li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox">
                <div class="ibox-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($id2 == "INC")
                            <h2>Log Incidente {{ $requerimiento->id2 }}</h2>
                            @else
                            <h2>Log Requerimiento {{ $requerimiento->id2 }}</h2>
                            @endif
                            <br>
                            <table class="table table-condensed">	
                                <tr>
                                    <td width="40%"><strong>Creado por</strong></td>
                                    <td width="60%">{{ $creador->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Creado el día</strong></td>
                                    <td>{{ $fecha_creacion }}</td>
                                </tr>
                                @if(count($elementos_log)!= 0)
                                    @foreach($elementos_log as $log)
                                        @if($log->tipo == "edicion")
                                            <tr>
                                                <td width="40%"><strong>Edición de {{$log->campo}}</strong></td>
                                                @foreach($usuarios_log as $usuario)
                                                    @if($log->idUsuario == $usuario->idUser)
                                                        <td width="60%">{{ $usuario->name }}</td>
                                                        @break
                                                    @endif
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha de edición</strong></td>
                                                <td>{{$log->created_at->format("d-m-Y")}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    @foreach($elementos_log as $log)
                                        @if($log->tipo == "terminar")
                                            <tr>
                                                <td width="40%"><strong>Terminado por</strong></td>
                                                @foreach($usuarios_log as $usuario)
                                                    @if($log->idUsuario == $usuario->idUser)
                                                        <td width="60%">{{ $usuario->name }}</td>
                                                        @break
                                                    @endif
                                                @endforeach                                    
                                            </tr>
                                            <tr>
                                                <td><strong>Terminado el día</strong></td>
                                                <td>{{$log->created_at->format("d-m-Y")}}</td>
                                            </tr>
                                            @break
                                        @endif
                                    @endforeach
                                    @foreach($elementos_log as $log)
                                        @if($log->tipo == "autorizar")
                                            <tr>
                                                <td width="40%"><strong>Autorizado por</strong></td>
                                                @foreach($usuarios_log as $usuario)
                                                    @if($log->idUsuario == $usuario->idUser)
                                                        <td width="60%">{{ $usuario->name }}</td>
                                                        @break
                                                    @endif
                                                @endforeach                                    
                                            </tr>
                                            <tr>
                                                <td><strong>Autorizado el día</strong></td>
                                                <td>{{$log->created_at->format("d-m-Y")}}</td>
                                            </tr>
                                            @break
                                        @endif
                                    @endforeach                                     
                                    @foreach($elementos_log as $log)                                    
                                        @if($log->tipo == "rechazar")
                                            <tr>
                                                <td width="40%"><strong>Rechazado por</strong></td>
                                                @foreach($usuarios_log as $usuario)
                                                    @if($log->idUsuario == $usuario->idUser)
                                                        <td width="60%">{{ $usuario->name }}</td>
                                                        @break
                                                    @endif
                                                @endforeach                                    
                                            </tr>
                                            <tr>
                                                <td><strong>Rechazado el día</strong></td>
                                                <td>{{$log->created_at->format("d-m-Y")}}</td>
                                            </tr>
                                            @break
                                        @endif
                                    @endforeach                                    
                                @endif
                            </table>
                        </div>
                    </div>
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
        if (window.innerWidth < 768) {
            $('.btn').addClass('btn-sm');
        }
    });
</script>
@endsection