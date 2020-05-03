<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Avance;
use App\Resolutor;
use App\Solicitante;
use App\Team;
use App\LogRequerimientos;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FinalizadoNotifi;
use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use DB;

class AvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requerimiento $requerimiento)
    {
        $avances = Avance::oldest('updated_at')->get();
        return view('Avances.index', compact('requerimiento', 'avances'));         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requerimiento $requerimiento)
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $lider = 0;
        if ($user[0]->nombre == "resolutor") {
            $resolutor = Resolutor::where('idUser', $user[0]->idUser)->first('lider');
            $lider = $resolutor->lider;           
        }
        
        if($requerimiento->fechaRealCierre != null){
            $fecha = new DateTime($requerimiento->fechaRealCierre);
        } else {
            $fecha = new DateTime($requerimiento->fechaCierre);
        }
        return view('Avances.create', compact('requerimiento', 'user', 'lider','fecha'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $data = request()->validate([
            'textAvance' => 'required',
            'idRequerimiento' => 'required'],
            ['textAvance.required' => 'El campo texto del avance es obligatorio']);
        $id = request()->validate([
            'idRequerimiento' => 'required']);

        Avance::create([
            'textAvance' => preg_replace("/[\r\n|\n|\r|\t]+/", " ", $data['textAvance']),
            'fechaAvance' => Carbon::now(),
            'idRequerimiento' => $data['idRequerimiento']
        ]);

        $idRequerimiento = $request->input("idRequerimiento");
        if ($request->input("fechaRealCierre") != null) {
            $requerimiento = Requerimiento::where([
                ['id', $request->input("idRequerimiento")]
            ])->first();
            if($requerimiento->fechaRealCierre != null){
                $fecha = new DateTime($requerimiento->fechaRealCierre);
            } else {
                $fecha = new DateTime($requerimiento->fechaCierre);
            }           
            if($request->input("fechaRealCierre")!=$fecha){
                
                $cambios=$requerimiento->numeroCambios;
                $fechaRealCierre = $request->input("fechaRealCierre");
                if ($cambios == null) {
                    $cambios = 1;                       
                }                      
                else {
                    $cambios +=1;             
                }

                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->idUser,
                    'tipo' => 'edición',
                    'campo' => 'fecha cierre resolutor',
                ]);
            }    
        } else {
            $requerimiento = DB::table('requerimientos')->select('fechaRealCierre')->where('id', $request->input("idRequerimiento"))->first();
            $fechaRealCierre = $requerimiento->fechaRealCierre;
            $requerimiento = DB::table('requerimientos')->select('numeroCambios')->where('id', $request->input("idRequerimiento"))->first();
            $cambios=$requerimiento->numeroCambios;                      
        }                               
          
        if ($request->input("porcentajeEjecutado") != null) {
            $porcentaje = $request->input("porcentajeEjecutado");
            if ($porcentaje == 100) 
            {
                $data = [
                    'estado' => 1,
                    'porcentajeEjecutado' => 100,
                    'cierre' => $data['textAvance'],
                    'aprobacion' => 4,
                ];

                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->idUser,
                    'tipo' => 'terminar',
                    'campo' => '',
                ]);
                
                $requerimiento = Requerimiento::where('id', $request->input('idRequerimiento'))->first();
                $resolutor = Resolutor::where('id', $requerimiento->resolutor)->first();
                $solicitante = Solicitante::where('id', $requerimiento->idSolicitante)->first();
                $resolutores = Resolutor::where('idTeam',Team::where('id',$resolutor->idTeam)->first()->id)->get();
                foreach ($resolutores as $resol) {
                    if ($resol->lider == 1) {
                        $lider = $resol;
                    }
                }            
                $obj = new \stdClass();
                $obj->idReq = $requerimiento->id2;
                $obj->id = $requerimiento->id;
                $obj->sol = $requerimiento->textoRequerimiento;
                $obj->nombre = $resolutor->nombreResolutor;
                $obj->solicitante = $solicitante->nombreSolicitante;

                $recep = $lider->email;

                //Notification::route('mail', $recep)->notify(new FinalizadoNotifi($obj));
            } else 
            {
                $data = [
                    'fechaRealCierre' => $fechaRealCierre,
                    'porcentajeEjecutado' => $porcentaje,
                    'numeroCambios' => $cambios,
                ];                
            }
        } else {
            $requerimiento = DB::table('requerimientos')->select('porcentajeEjecutado')->where('id', $request->input("idRequerimiento"))->first();
            $porcentaje = $requerimiento->porcentajeEjecutado; 
            $data = [
                'fechaRealCierre' => $fechaRealCierre,
                'porcentajeEjecutado' => $porcentaje,
                'numeroCambios' => $cambios,
            ];                   
        }

        DB::table('requerimientos')->where('id', $request->input('idRequerimiento'))->update($data);            
                                
        return redirect(url("requerimientos/$request->idRequerimiento"));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response

    public function show(Avance $avance)
    {
        //
    }
    */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento, Avance $avance)
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        return view('Avances.edit', compact('requerimiento', 'avance', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function update(Requerimiento $requerimiento, Request $request, Avance $avance)
    {
        
        $data = request()->validate([
            'idAvance' => 'required',
            'textAvance' => 'required'
        ]);

        $data['textAvance'] = preg_replace("/[\r\n|\n|\r|\t]+/", " ", $data['textAvance']);

        $avance->update($data);
        return redirect(url("requerimientos/$requerimiento->id"));        

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Avance  $avance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requerimiento $requerimiento, Avance $avance)
    {
        
      $avance->delete();
      return redirect(url("requerimientos/$requerimiento->id"));
    }
}
