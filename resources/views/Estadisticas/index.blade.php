@extends('Bases.dashboard')
@section('titulo', "Estadísticas")

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />
@endsection

@section('contenido')
    {{-- <div class="page-heading">
        <h1 class="page-title"><i class="fa fa-pie-chart"></i> Estadísticas</h1>
    </div> --}}
    <div class="page-content fade-in-up">
        <div class="ibox">
            <form action="{{ route('estadisticas.filtro') }}" method="POST">
                @csrf
                <div class="ibox-body row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-dark"><i class="fa fa-calendar-check-o"></i> Período</small>
                                <select name="rango_fecha" id="rango_fecha" class="form-control" onchange="validarTipo(this.value)">
                                    <option value="mes_actual" @isset($data) @if ($data['rango_fecha'] == 'mes_actual') selected @endif @endisset >Mes actual</option>
                                    <option value="mes_ult3" @isset($data) @if ($data['rango_fecha'] == 'mes_ult3') selected @endif @endisset>Últimos 3 meses</option>
                                    <option value="mes_ult6" @isset($data) @if ($data['rango_fecha'] == 'mes_ult6') selected @endif @endisset>Últimos 6 meses</option>
                                    <option value="mes_ult12" @isset($data) @if ($data['rango_fecha'] == 'mes_ult12') selected @endif @endisset>Últimos 12 meses</option>
                                    <option value="por_rango" @isset($data) @if ($data['rango_fecha'] == 'por_rango') selected @endif @endisset>Por rango</option>
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
                                <button type="submit" class="btn btn-success btn-block text-white" style="cursor:pointer"><i class="fa fa-filter"></i> Filtrar</button>
                            </div>
                            <div class="col-md-6">
                                <small>&nbsp;</small>
                                <a class="btn btn-warning btn-block text-white" href="{{ route ('estadisticas.index')}}"><i class="fa fa-repeat"></i> Reiniciar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('Estadisticas.generales')

    @endsection

@section('script')
    <script src="{{ asset('js/moment.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/moment-with-locales.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/fusioncharts/js/fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/fusioncharts/integrations/jquery/js/jquery-fusioncharts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/fusioncharts/js/themes/fusioncharts.theme.fusion.js') }}"></script>
	<script type="text/javascript">
        menu_activo('mEstadisticas');
<<<<<<< HEAD
=======

>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
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
<<<<<<< HEAD
=======

>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
        $(function () {
            $('#fec_des').datetimepicker({
                locale: 'es',
                useCurrent: false,
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,
                forceParse: false,
                maxDate: 'now',
            });
<<<<<<< HEAD
=======

>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
            @isset($data)
                @if ($data['desde']!="" && $data['desde']!=null && $data['rango_fecha']=="por_rango")
                    $('#fec_des').prop('disabled', false);
                    $('#fec_des').val('{{ date("d/m/Y", strtotime($data["desde"])) }}');
                @endif
            @endisset
            
            $('#fec_has').datetimepicker({
                locale: 'es',
                useCurrent: false,
                format: 'DD/MM/YYYY',
                ignoreReadonly: true,
                forceParse: false,
                maxDate: 'now',
            });
<<<<<<< HEAD
=======

>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
            @isset($data)
                @if ($data['hasta']!="" && $data['hasta']!=null && $data['rango_fecha']=="por_rango")
                    $('#fec_has').prop('disabled', false);
                    $('#fec_has').val('{{ date("d/m/Y", strtotime($data["hasta"])) }}');
                @endif
            @endisset
            
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
    
<<<<<<< HEAD
@endsection
=======
@endsection
>>>>>>> 3b4c0926a9818fd4677ab35adddeafed324fe973
