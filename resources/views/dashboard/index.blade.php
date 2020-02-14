@extends('Bases.dashboard')
@section('titulo', 'Tablero')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@endsection

@section('contenido')
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-body row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-dark"><i class="fa fa-calendar-check-o"></i> Período</small>
                        <select name="rango_fecha" id="rango_fecha" class="form-control" onchange="validarTipo(this.value)">
                            <option value="mes_actual">Mes actual</option>
                            <option value="mes_ult3">Últimos 3 meses</option>
                            <option value="mes_ult6">Últimos 6 meses</option>
                            <option value="mes_ult12">Últimos 12 meses</option>
                            <option value="por_rango">Por rango</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <small class="text-dark"><i class="fa fa-calendar"></i> Rango de fecha</small>
                        <div class="input-daterange input-group" id="datepicker">
                            <span class="input-group-addon p-l-10 p-r-10"><small class="text-dark">desde</small></span>
                            <input type="text" class="form-control datetimepicker-input" id="fec_des" name="fec_des" value="" data-toggle="datetimepicker" data-target="#fec_des" maxlength="16" disabled/>
                            <span class="input-group-addon p-l-10 p-r-10"><small class="text-dark">hasta</small></span>
                            <input type="text" class="form-control datetimepicker-input" id="fec_has" name="fec_has" value="" data-toggle="datetimepicker" data-target="#fec_has" maxlength="16" disabled/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6">
                        <small>&nbsp;</small>
                        <a class="btn btn-success btn-block text-white" href="#"><i class="fa fa-filter"></i> Filtrar</a>
                    </div>
                    <div class="col-md-6">
                        <small>&nbsp;</small>
                        <a class="btn btn-warning btn-block text-white" href="{{ route ('tablero')}}"><i class="fa fa-repeat"></i> Reiniciar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-success color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">201</h2>
                    <div class="m-b-5">REQUERIMIENTOS</div><i class="fa fa-address-card widget-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-info color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">32</h2>
                    <div class="m-b-5">RESOLUTORES</div><i class="fa fa-address-book widget-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-warning color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">50</h2>
                    <div class="m-b-5">SOLICITANTES</div><i class="fa fa-address-book-o widget-stat-icon"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="ibox bg-danger color-white widget-stat">
                <div class="ibox-body">
                    <h2 class="m-b-5 font-strong">24</h2>
                    <div class="m-b-5">EQUIPOS</div><i class="fa fa-users widget-stat-icon"></i>
                </div>
            </div>
        </div>
    </div>
    @if ($user[0]->nombre == "administrador" || $user[0]->nombre == "supervisor")
        @include('dashboard.administrador')
    @endif
    @if($user[0]->nombre == 'solicitante')
        @include('dashboard.solicitante')
    @endif
    @if($user[0]->nombre == 'resolutor' && $resolutor_lider == 0)
        @include('dashboard.resolutor')
    @endif
    @if($user[0]->nombre == 'resolutor' && $resolutor_lider == 1)
        @include('dashboard.resolutor_lider')
    @endif
@endsection

@section('script')
    <script src="{{ asset('js/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/moment-with-locales.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/fusioncharts/js/fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/fusioncharts/integrations/jquery/js/jquery-fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/fusioncharts/js/themes/fusioncharts.theme.fusion.js') }}"></script>
    <script type="text/javascript">
        menu_activo('mTablero');
        function validarTipo(valor) {
            if (valor=='por_rango') {
                $('#fec_des').prop('disabled', false);
                $('#fec_has').prop('disabled', false);
            } else {
                $('#fec_des').val("");
                $('#fec_has').val("");
                $('#fec_des').prop('disabled', true);
                $('#fec_has').prop('disabled', true);
            }
        }
        $(function () {
            $('#fec_des').datetimepicker({
                locale: 'es',
                useCurrent: false,
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,
                forceParse: false,
                maxDate: 'now',
            });
            
            $('#fec_has').datetimepicker({
                locale: 'es',
                useCurrent: false,
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,
                forceParse: false,
                maxDate: 'now',
            });
            
            // Valida que la fecha hasta no sea menor que fecha desde
            $("#fec_des").on("change.datetimepicker", function (e) {
                $('#fec_has').datetimepicker('minDate', e.date);
            });
            $("#fec_has").on("change.datetimepicker", function (e) {
                $('#fec_des').datetimepicker('maxDate', e.date);
            });
        });
    </script>
    
    @yield('scripts_dash')
@endsection