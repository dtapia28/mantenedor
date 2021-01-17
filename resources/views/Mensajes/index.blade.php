@extends('Bases.dashboard')

@section('css')
	<link href="{{ asset('vendor/DataTables/datatables.min.css') }}" rel="stylesheet" />
@endsection

@section('titulo', 'Mensajes')

@section('contenido')
@if(session()->has('msj'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('msj') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
@endif
<div class="page-heading">
	<h1 class="page-title"><i class="fa fa-comments-o"></i> Mensajes</h1>
</div>
<div class="page-content fade-in-up">
	<div class="ibox">
		<div class="ibox-head">
			<div class="ibox-title">Bandeja de mensajes</div>
			{{-- @if($user[0]->nombre == "administrador") --}}
				<div class="pull-right"><a class="btn btn-success" href="{{ url('mensajes/nuevo') }}" style="white-space: normal;"><i class="fa fa-plus"></i> Nuevo Mensaje</a></div>
			{{-- @endif --}}
		</div>
		<div class="ibox-body">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
                            <th scope="col"><strong>De</strong></th>
                            <th scope="col"><strong>Para</strong></th>
                            <th scope="col"><strong>Asunto</strong></th>
                            <th scope="col"><strong>Fecha</strong></th>
							@if($user[0]->nombre == "administrador")
							<th scope="col"><strong>Acciones</strong></th>
							@endif
						</tr>
					</thead>
					<tbody>
                        @if (count($mensajes) > 0)
                            @foreach($mensajes as $item)
                            <tr @if($item->leido==0) class="font-weight-bold" @endif id="fila{{$item->id}}">
                                <td>{{$item->de_name}}</td>
                                <td>{{$item->para_name}}</td>
                                <td><span onclick="mostrarMensaje({{$item->id}})"><a href="#">{{$item->asunto}}</a></span></td>
                                <td>{{date('d/m/Y H:i:s', strtotime($item->fecha))}}</td>
                                <td>
                                    <div scope="row" class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-original-title="Leer" style="cursor:pointer" onclick="mostrarMensaje({{$item->id}})"><i class="fa fa-comment-o"></i></button>
                                        &nbsp;
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Eliminar" onclick="validaEliminar({{$item->id}})" style="cursor: pointer"><i class="fa fa-trash font-14"></i></button>
                                        <form id="delete-form{{$item->id}}" action="{{ route('mensajes.delete', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="{{$item->id}}">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No hay mensajes para mostrar</td>
                            </tr>
                        @endif
					</tbody>		
				</table>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="dataModalMsg" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Detalle del Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-sm" id="tablaModalSol" style="font-size: 1em">
                        <tr>
                            <td width="15%"><strong>De</strong></td>
                            <td><span id="datoDe"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Para</strong></td>
                            <td><span id="datoPara"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Asunto</strong></td>
                            <td><span id="datoAsunto"></span></td>
                        </tr>
                        <tr>
                            <td><strong>Fecha</strong></td>
                            <td><span id="datoFecha"></span></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center"><strong>Mensaje</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2"><span id="datoMensaje"></span></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/DataTables/datatables.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
	menu_activo('mMensajes');
	$(function() {
		$('#dataTable').DataTable({
			"language": {
				"url": "{{ asset('vendor/DataTables/lang/spanish.json') }}"
			},
			pageLength: 10,
			stateSave: true,
		});
	});
	$(document).ready(function() {
        $.ajaxSetup({
            headers:
            { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
		if (window.innerWidth < 768) {
			$('.btn').addClass('btn-sm');
		}
    });
    
    function mostrarMensaje(id) {
        $("#dataModalMsg").modal("show");
        let user_id = '<?=$user[0]->idUser?>';
        let para = "";
        
        $.ajax({
            type: 'post',
            url: 'mensajes/show',
            data: {
                'id': id
            },
            dataType: 'json',
            success: function (data) {
                if (data.respuesta) {
                    $('#datoDe').text(data.msg[0]['de_name']);
                    $('#datoPara').text(data.msg[0]['para_name']);
                    $('#datoAsunto').text(data.msg[0]['asunto']);
                    $('#datoFecha').text(data.msg[0]['fecha']);
                    $('#datoMensaje').text(data.msg[0]['mensaje']);
                    para = data.msg[0]['para'];
                } else {
                    console.log("El mensaje no pudo ser cargado");
                    return;
                }
            },
            complete: function (data) {
                if (para == user_id)
                    $('#fila'+id).removeClass('font-weight-bold');
            },
            error: function (data) {
                console.log('Error:', data);
                alert("Error al consultar el mensaje");
            }
        });
    }

    function validaEliminar(id) {
        if (confirm('¿Confirma Eliminar el Mensaje '+id+'?')) {
            event.preventDefault();
            document.getElementById('delete-form'+id).submit();
        }
    }

</script>
@endsection
