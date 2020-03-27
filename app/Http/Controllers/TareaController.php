<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Requerimiento;
use App\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Resolutor;
use DateTime;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requerimiento $requerimiento)
    {
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get(); 
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }        
        return view('Tareas.create', compact('requerimiento', 'user', 'teams', 'lider'));       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = request()->validate([
            'fechaSolicitud' => 'required',
            'fechaCierre' => 'required',
            'texto' => 'required',
            'idResolutor' => 'required',
            'idRequerimiento' => 'required'],
            ['fechaSolicitud.required' => 'La fecha de solicitud es obligatoria'],
            ['fechaCierre.required' => 'La fecha de cierre es obligatoria'],
            ['texto.required' => 'El texto de la tarea es obligatorio']);            

        $fechaSoli = new DateTime($data['fechaSolicitud']);
        $fechaCie = new DateTime($data['fechaCierre']);
        if ($fechaCie->getTimestamp() >= $fechaSoli->getTimestamp()) 
        {
            $formato = "0";
            $requerimiento = Requerimiento::where('id', $data['idRequerimiento'])->get();
            $tareasReq = Tarea::where('idRequerimiento', $data['idRequerimiento'])->count();
            if ($tareasReq >= 0) {
                $tareasReq++;
            }
            if ($tareasReq < 10) {
                $formato = $formato.$tareasReq;
            }
            Tarea::create([
                'textoTarea' => $data['texto'],
                'fechaSolicitud' => $data['fechaSolicitud'],
                'fechaCierre' => $data['fechaCierre'],
                'idRequerimiento' => $data['idRequerimiento'],
                'resolutor' => $data['idResolutor'],
                'id2' => $requerimiento['0']->id2."-".$formato,
            ]);
        }

        return redirect(url("requerimientos/$request->idRequerimiento"));        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function show(Tarea $tarea)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento, Tarea $tarea)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }           
        $fechita = str_split($tarea->fechaCierre);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $cierre = implode("", $fechota);

        $fechita = str_split($tarea->fechaSolicitud);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $solicitud = implode("", $fechota);               
        return view('Tareas.edit', compact('cierre', 'solicitud', 'tarea', 'requerimiento', 'user', 'lider')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $tarea = Tarea::where('id', $request->tarea)->first();
        $data = [
            'fechaSolicitud' => $request->fechaSolicitud,
            'fechaCierre' => $request->fechaCierre,
            'textoTarea' => $request->texto,
        ];

        $tarea->update($data);

        return redirect(url("requerimientos/$request->req"));
    }

    public function terminar(Request $request)
    {

        $tarea = Tarea::where('id', $request->tarea)->first();
        $requerimiento = Requerimiento::where('id', $tarea->idRequerimiento)->first();
        $tareas = Tarea::where([
            ['idRequerimiento', $requerimiento->id],
            ['estado', 1],
        ])->get();
        $cTareas = count($tareas);
        if($requerimiento->porcentajeEjecutado == null or $requerimiento->porcentajeEjecutado == 0)
        {
            $porcentaje = 100/$cTareas;
        } else
        {
            $porcentaje = $requerimiento->porcentajeEjecutado+((100-$requerimiento->porcentajeEjecutado)/$cTareas);
        }
        
        $data = [
            'estado' => 2];   
        $tarea->update($data);
        
        if($porcentaje == 100){
            $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
            $lider = 0;
            if ($user[0]->nombre == "resolutor") {
                $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
                $lider = $resolutor->lider;           
            }       
            return view('Requerimientos.terminado', compact('requerimiento', 'user', 'lider'));            
        } else
        {
            $data = [
                'porcentajeEjecutado'=>$porcentaje
            ];
            $requerimiento->update($data);            
        }

        return redirect(url("requerimientos/$request->req"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $tarea = Tarea::where('id', $request->tarea)->first();
        $data = [
            'estado' => 0,
        ];

        $tarea->update($data);

        return redirect(url("requerimientos/$request->req"));        
    }
}
