@extends('Bases.dashboard')

@section('titulo', "Indicadores")

@section('contenido')
    <div class="page-heading">
        <h1 class="page-title"><i class="fa fa-tachometer"></i> Indicadores</h1>
    </div>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-lg-5 col-md-5">
                <div class="ibox">
                    <div class="ibox-body text-center">
                        <i class="fa fa-certificate fa-2x text-warning"></i>
                        <div class="m-b-20 text-dark">Resolutor Destacado {{$mes_texto}}</div>
                        <div class="m-t-20"><i class="fa fa-address-book fa-3x"></i></div>
                        <h5 class="font-strong m-b-10 m-t-10">{{ $destacadoNombre }}</h5>
                        <h5 class="text-info m-b-20 m-t-10">Requerimientos Cerrados Al Día</h5>
                        <div class="h2 m-0">{{ number_format($destacadoAlDia, 0, ',', '.') }}</div>
                        <div class="mt-4"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7">
                <div class="ibox">
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab-1" data-toggle="tab"><i class="ti-bar-chart"></i> Requerimientos cerrados por estatus {{$mes_texto}}</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-1">
                                <h5 class="text-info m-b-20 m-t-20"><i class="fa fa-bullhorn"></i> Cantidad total de ...</h5>
                                <ul class="media-list media-list-divider m-0">
                                    <li class="media">
                                        <div class="media-img"><i class="ti-check font-18 text-muted"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-success">Requerimientos cerrados al día <div class="float-right text-dark h4 m-0">{{ $alDia }}</div></div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="ti-info-alt font-18 text-muted"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-warning">Requerimientos cerrados por vencer <div class="float-right text-dark h4 m-0">{{ $vencer }}</div></div>
                                        </div>
                                    </li>
                                    <li class="media">
                                        <div class="media-img"><i class="ti-reload font-18 text-muted"></i></div>
                                        <div class="media-body">
                                            <div class="media-heading text-danger">Requerimientos cerrados vencidos <div class="float-right text-dark h4 m-0">{{ $vencido }}</div></div>
                                        </div>
                                    </li>                                    
                                </ul>
                            </div>
                            <div class="mt-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
	<script type="text/javascript">
        menu_activo('mIndicadores');
	</script>
@endsection


