<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Team;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        $desde = date('Y-m-').'01';
        $hasta = date('Y-m-d');

        // INDICADORES
        $reqAb = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                ->where('estado', 1)
                                ->where('aprobacion', 3)
                                ->whereBetween('fechaSolicitud', [$desde, $hasta])
                                ->get();
        
        $reqCe = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                ->where('estado', 2)
                                ->whereBetween('fechaSolicitud', [$desde, $hasta])
                                ->get();
        
        $total = intval(count($reqAb)) + intval(count($reqCe));

        $indicadores = ['abiertos' => count($reqAb), 'cerrados' => count($reqCe), 'total' => $total];

        // Gráficos porcentaje/cantidad de requerimientos por área
        $req_area = DB::select("SELECT c.id, c.nameTeam area, COUNT(*) cant_reqs, ROUND(AVG(DATEDIFF(a.fechaCierre, a.fechaSolicitud))) sla_solucion
                                FROM requerimientos a
                                JOIN resolutors b ON a.resolutor=b.id
                                JOIN teams c ON b.idTeam=c.id
                                WHERE a.rutEmpresa = ? AND a.estado IN ('1', '2', '3') AND aprobacion='3' AND a.fechaSolicitud BETWEEN ? AND ?
                                GROUP BY c.id", [auth()->user()->rutEmpresa, $desde." 00:00:00", $hasta." 23:59:59"]);
        $array_areas = [];
        foreach ($req_area as $item) {
            array_push($array_areas, ['label' => $item->area, 'value' => $item->cant_reqs]);
        }
        $req_area1 = json_encode($array_areas);

        // Gráfico requerimientos por mes
        $desdeAnual = date("Y-m-d", strtotime("-12 month"));
        $hastaAnual = date('Y-m-d', strtotime("-1 month"));
        $sql_req = DB::select("SELECT c.id, c.nameTeam area, SUBSTR(a.fechaSolicitud, 1, 7) periodo, COUNT(*) cant_reqs
                                FROM requerimientos a
                                JOIN resolutors b ON a.resolutor=b.id
                                JOIN teams c ON b.idTeam=c.id
                                WHERE a.rutEmpresa = ? AND a.fechaSolicitud BETWEEN ? AND ?
                                GROUP BY c.id, SUBSTR(a.fechaSolicitud, 1, 7)
                                ORDER BY periodo, area", [auth()->user()->rutEmpresa, $desdeAnual." 00:00:00", $hastaAnual." 23:59:59"]);
        
        $areasArray = [];
        foreach ($sql_req as $item) {
            if (!in_array($item->area, $areasArray)) {
                array_push($areasArray, $item->area);
            }
        }

        $array_series = [];
        $array_data = [];
        
        $model = [];
        for ($i=0; $i<count($sql_req); $i++) {
            if (!in_array($sql_req[$i]->periodo, $array_series)) {
                array_push($array_series, $sql_req[$i]->periodo);
                $model[$i]["seriesname"] = $sql_req[$i]->periodo;
                for ($j=0; $j<count($sql_req); $j++) {
                    if ($sql_req[$i]->periodo === $sql_req[$j]->periodo) {
                        $model[$i]["data"][]["value"] = $sql_req[$j]->cant_reqs;
                    }
                }
            }
        }
        
        $array_data = (json_encode(array_values($model)));
        
        // Json con datos de las áreas        
        $areasJson = [];
        foreach ($areasArray as $item) {
            array_push($areasJson, ["label" => $item]);
        }
        
        $areas = json_encode($areasJson);
        
        // Datos para la tabla requerimiento por semana
        $mes  = date('m');
        $anio = date('Y');
        $semanas = ($this->semanasMes($mes, $anio));
        $tabla_sem = [];
        
        foreach ($semanas as $key => $value) {
            if ($value["inicio"] <= date('d')) {
                // $value["inicio"]."-".$value["fin"];
                $desde_sem = $anio."-".$mes."-".$value["inicio"]." 00:00:00";
                $hasta_sem = $anio."-".$mes."-".$value["fin"]." 23:59:59";

                $reqAb = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                    ->where('estado', 1)
                                    ->where('aprobacion', 3)
                                    ->whereBetween('fechaSolicitud', [$desde_sem, $hasta_sem])
                                    ->get();
            
                $reqCe = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                    ->where('estado', 2)
                                    ->whereBetween('fechaSolicitud', [$desde_sem, $hasta_sem])
                                    ->get();

                $data_sem = ['semana' => ($key+1), 'abiertos' => count($reqAb), 'cerrados' => count($reqCe)];
                array_push($tabla_sem, $data_sem);
            }
        }
        
        return view('Estadisticas.index', compact('user', 'indicadores', 'req_area', 'req_area1', 'areas', 'array_data', 'tabla_sem'));
    }

    public function filtro(Request $request)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        $rango_fecha = $request->rango_fecha;
        switch ($rango_fecha) {
            case 'mes_actual':
                $desde = date('Y-m-').'01';
                $hasta = date('Y-m-d');
                break;
            case 'mes_ult3':
                $desde = date("Y-m-d", strtotime("-3 month"));
                $hasta = date('Y-m-d');
                break;
            case 'mes_ult6':
                $desde = date("Y-m-d", strtotime("-6 month"));
                $hasta = date('Y-m-d');
                break;
            case 'mes_ult12':
                $desde = date("Y-m-d", strtotime("-12 month"));
                $hasta = date('Y-m-d');
                break;
            case 'por_rango':
                $desde = substr($request->fec_des, 6, 4).'-'.substr($request->fec_des, 3, 2).'-'.substr($request->fec_des, 0, 2);
                $hasta = substr($request->fec_has, 6, 4).'-'.substr($request->fec_has, 3, 2).'-'.substr($request->fec_has, 0, 2);
                break;
            default:
                $desde = date('Y-m-').'01';
                $hasta = date('Y-m-d');
                break;
        }

        // INDICADORES
        $reqAb = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                ->where('estado', 1)
                                ->where('aprobacion', 3)
                                ->whereBetween('fechaSolicitud', [$desde, $hasta])
                                ->get();
        
        $reqCe = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                ->where('estado', 2)
                                ->whereBetween('fechaSolicitud', [$desde, $hasta])
                                ->get();
        
        $total = intval(count($reqAb)) + intval(count($reqCe));

        $indicadores = ['abiertos' => count($reqAb), 'cerrados' => count($reqCe), 'total' => $total];

        $data['desde'] = $desde;
        $data['hasta'] = $hasta;
        $data['rango_fecha'] = $rango_fecha;

        // Gráfico % de requerimientos por área
        $req_area = DB::select("SELECT c.id, c.nameTeam area, COUNT(*) cant_reqs, ROUND(AVG(DATEDIFF(a.fechaCierre, a.fechaSolicitud))) sla_solucion
                                FROM requerimientos a
                                JOIN resolutors b ON a.resolutor=b.id
                                JOIN teams c ON b.idTeam=c.id
                                WHERE a.rutEmpresa = ? AND a.estado IN ('1', '2', '3') AND aprobacion='3' AND a.fechaSolicitud BETWEEN ? AND ?
                                GROUP BY c.id", [auth()->user()->rutEmpresa, $desde." 00:00:00", $hasta." 23:59:59"]);
        $array_areas = [];
        foreach ($req_area as $item) {
            array_push($array_areas, ['label' => $item->area, 'value' => $item->cant_reqs]);
        }
        $req_area1 = json_encode($array_areas);

        // Gráfico requerimientos por mes
        $desdeAnual = date("Y-m-d", strtotime("-12 month"));
        $hastaAnual = date('Y-m-d', strtotime("-1 month"));
        $sql_req = DB::select("SELECT c.id, c.nameTeam area, SUBSTR(a.fechaSolicitud, 1, 7) periodo, COUNT(*) cant_reqs
                                FROM requerimientos a
                                JOIN resolutors b ON a.resolutor=b.id
                                JOIN teams c ON b.idTeam=c.id
                                WHERE a.rutEmpresa = ? AND a.fechaSolicitud BETWEEN ? AND ?
                                GROUP BY c.id, SUBSTR(a.fechaSolicitud, 1, 7)
                                ORDER BY periodo, area", [auth()->user()->rutEmpresa, $desdeAnual." 00:00:00", $hastaAnual." 23:59:59"]);
        
        $areasArray = [];
        foreach ($sql_req as $item) {
            if (!in_array($item->area, $areasArray)) {
                array_push($areasArray, $item->area);
            }
        }

        $array_series = [];
        $array_data = [];
        
        $model = [];
        for ($i=0; $i<count($sql_req); $i++) {
            if (!in_array($sql_req[$i]->periodo, $array_series)) {
                array_push($array_series, $sql_req[$i]->periodo);
                $model[$i]["seriesname"] = $sql_req[$i]->periodo;
                for ($j=0; $j<count($sql_req); $j++) {
                    if ($sql_req[$i]->periodo === $sql_req[$j]->periodo) {
                        $model[$i]["data"][]["value"] = $sql_req[$j]->cant_reqs;
                    }
                }
            }
        }
        
        $array_data = (json_encode(array_values($model)));

        // Json con datos de las áreas        
        $areasJson = [];
        foreach ($areasArray as $item) {
            array_push($areasJson, ["label" => $item]);
        }

        $areas = json_encode($areasJson);

        // Datos para la tabla requerimiento por semana
        $mes  = date('m');
        $anio = date('Y');
        $semanas = ($this->semanasMes($mes, $anio));
        $tabla_sem = [];
        
        foreach ($semanas as $key => $value) {
            if ($value["inicio"] <= date('d')) {
                // $value["inicio"]."-".$value["fin"];
                $desde_sem = $anio."-".$mes."-".$value["inicio"]." 00:00:00";
                $hasta_sem = $anio."-".$mes."-".$value["fin"]." 23:59:59";

                $reqAb = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                    ->where('estado', 1)
                                    ->where('aprobacion', 3)
                                    ->whereBetween('fechaSolicitud', [$desde_sem, $hasta_sem])
                                    ->get();
            
                $reqCe = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)
                                    ->where('estado', 2)
                                    ->whereBetween('fechaSolicitud', [$desde_sem, $hasta_sem])
                                    ->get();

                $data_sem = ['semana' => ($key+1), 'abiertos' => count($reqAb), 'cerrados' => count($reqCe)];
                array_push($tabla_sem, $data_sem);
            }
        }
        // dd($tabla_sem);
        return view('Estadisticas.index', compact('user', 'data', 'indicadores', 'req_area', 'areas', 'array_data', 'req_area1', 'tabla_sem'));
    }

    public function semanasMes($mes, $anio)
	{		
		$ultimo_dia = date("d", mktime(0, 0, 0, $mes+1, 0, $anio));
		$semanas = array();
		$cantidad_semanas = 0;
		$inicio = 1;
		$fin = 0;
		$dia_semana = '';
		for($i = 1; $i<=$ultimo_dia; $i++)
		{
			$fecha = mktime(0, 0, 0, $mes, $i, $anio);
			$dia_semana = date('w', ($fecha));
			if($dia_semana == 0)
			{
				$semanas[$cantidad_semanas] = array('inicio' => $inicio,'fin'=>$i);
				$inicio = $i + 1;
				$cantidad_semanas++;
			}
		}
		$ultima_semana = end($semanas);
		if($ultima_semana['fin'] != $ultimo_dia)
		{
			$semanas[$cantidad_semanas] = array('inicio' => $inicio,'fin' => $ultimo_dia);
		}
		return $semanas;
	}
}
