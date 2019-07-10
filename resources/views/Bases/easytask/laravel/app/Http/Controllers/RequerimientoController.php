<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Empresa;
use App\Resolutor;
use App\Priority;
use App\Solicitante;
use App\Avance;
use App\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use DateTime;
use DateInterval;

class RequerimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $resolutors = Resolutor::all();
        $teams = Team::all();
        if ($request->state != ""){
            $request->session()->put('state', $request->state);
        } else {
            $request->session()->put('state', 1);
        }
        $requerimientos = Requerimiento::where('estado', $request->session()->get('state'))->get();
        //$requerimientos->appends(['state'=>$request->session()->get('state')])->links();
        $valor = 1;
        if ($request->session()->get('state') == 1) {
            $valor = 1;
        }else {
            $valor = 0;
        }

        return view('Requerimientos.index', compact('requerimientos', 'resolutors', 'teams', 'valor'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresas = Empresa::all();
        $resolutors = Resolutor::all();
        $priorities = Priority::all();
        $solicitantes = Solicitante::all();

        return view('Requerimientos.create', compact('empresas', 'resolutors', 'priorities', 'solicitantes'));        
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
            'textoRequerimiento' => 'required',
            'fechaEmail' => 'required',
            'fechaSolicitud' => 'required',
            'fechaCierre' => 'required',
            'fechaRealCierre' => 'nullable',
            'numeroCambios' => 'nullable',
            'porcentajeEjecutado' => 'nullable',
            'cierre' => 'nullable',
            'idSolicitante' => 'required',
            'idPrioridad' => 'required',
            'idResolutor' => 'required',
            'idEmpresa' => 'required'],
            ['nombreResolutor.required' => 'El campo nombre es obligatorio'],
            ['fechaEmail.required' => 'La fecha de email es obligatoria'],
            ['fechaSolicitud' => 'La fecha de solicitud es obligatoria'],
            ['fechaCierre' => 'La fecha de cierre es obligatoria']);


        Requerimiento::create([
            'textoRequerimiento' => $data['textoRequerimiento'],            
            'fechaEmail' => $data['fechaEmail'],
            'fechaSolicitud' => $data['fechaSolicitud'],
            'fechaCierre' => $data['fechaCierre'],
            'idSolicitante' => $data['idSolicitante'],
            'idPrioridad' => $data['idPrioridad'],
            'idResolutor' => $data['idResolutor'],
            'idEmpresa' => $data['idEmpresa'],
        ]);

        return redirect('requerimientos');        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Requerimiento $requerimiento)
    {
        $avances = Avance::where('idRequerimiento', $requerimiento->id)->latest('created_at')->paginate(9);
        $resolutors = Resolutor::all();
        $priorities = Priority::all();
        $teams = Team::all();
        $fechaCierre = new DateTime($requerimiento->fechaCierre);
        $fechaSolicitud = new DateTime($requerimiento->fechaSolicitud);
        $fechaRealCierre = new DateTime($requerimiento->fechaRealCierre);
        $hastaCierre = 0;
        $variable = $fechaSolicitud;
        while ($variable->getTimestamp() <= $fechaCierre->getTimestamp()) {
            if ($variable->format('l') == 'Saturday' or $variable->format('l') == 'Sunday') {
                $variable->modify("+1 days");
            }else{
                $hastaCierre++;
                $variable->modify("+1 days");                           
            }
        }

        $hastaHoy = 0;
        $hoy = new DateTime();
        if ($requerimiento->estado == 1) {
            $variable = new DateTime($requerimiento->fechaSolicitud);           
            while ($variable->getTimestamp() <= $hoy->getTimestamp()) {
                if ($variable->format('l') == 'Saturday' or $variable->format('l') == 'Sunday') {
                    $variable->modify("+1 days");                    
                } else {
                    $hastaHoy++;
                    $variable->modify("+1 days");
                }
            }

        } else 
        {
            if ($requerimiento->fechaRealCierre != null) {
                $variable = new DateTime($requerimiento->fechaSolicitud);             
                while ($variable->getTimestamp() <= $fechaRealCierre->getTimestamp()) {
                    if ($variable->format('l') == 'Saturday' or $variable->format('l') == 'Sunday') {
                        $variable->modify("+1 days");                    
                    } else {
                        $hastaHoy++;
                        $variable->modify("+1 days");
                    }
                }                
            } else
            {
                $variable = new DateTime($requerimiento->fechaSolicitud);               
                while ($variable->getTimestamp() <= $fechaCierre->getTimestamp()) {
                    if ($variable->format('l') == 'Saturday' or $variable->format('l') == 'Sunday') {
                        $variable->modify("+1 days");                    
                    } else {
                        $hastaHoy++;
                        $variable->modify("+1 days");
                    }
                }                
            }
        }

        $restantes = 0;
        $comienzo = new DateTime();
        $fechaFinal;
            
            if ($requerimiento->fechaRealCierre != null) {

                $fechaFinal = new DateTime($requerimiento->fechaRealCierre);

            } else {

                $fechaFinal = new DateTime($requerimiento->fechaCierre);

            }

            if ($comienzo>$fechaFinal) {
                    
            } else {
                while ($comienzo->getTimestamp() > $fechaFinal->getTimestamp()){

                    if ($fechaFinal->format('l') == 'Saturday' or $fechaFinal->format('l') == 'Sunday'){
                        $fechaFinal->modify("+1 days");                         
                    } else {
                        $restantes++;
                        $fechaFinal->modify("+1 days");                        
                    }
                }
            }
         

        return view('Requerimientos.show', compact('requerimiento', 'resolutors', 'priorities', 'avances', 'teams', "hastaCierre", "hastaHoy", "restantes", "hoy", "fechaCierre"));        
        }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento)
    {
        $solicitantes = Solicitante::all();
        $priorities = Priority::all();
        $resolutors = Resolutor::all();
        $empresas = Empresa::all();

        return view('Requerimientos.edit', compact('requerimiento', 'solicitantes', 'priorities', 'resolutors', 'empresas'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requerimiento $requerimiento)
    {
        $data = request()->validate([
            'textoRequerimiento' => 'nullable',
            'idSolicitante' => 'nullable',
            'idrioridad' => 'nullable',
            'idResolutor' => 'nullable',
            'idEmpresa' => 'nullable'
        ]);
        $requerimiento->update($data);
        return redirect('requerimientos');         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requerimiento $requerimiento)
    {
        $avances = Avance::where('idRequerimiento', $requerimiento->id)->get();
        foreach($avances as $avance){
            $avance->delete();
        }
        $requerimiento->delete();
        return redirect('requerimientos');   
    }

    public function actualizar(Requerimiento $requerimiento)
    {
        return view('Requerimientos.actualizar', compact('requerimiento'));        
    }

    public function save(Request $request, Requerimiento $requerimiento)
    {
        if ($request->fechaRealCierre != "") {
            $cambios=$requerimiento->numeroCambios;
            if ($cambios == null) {
                $cambios = 1;
                $request->merge(['numeroCambios' => $cambios]);
                $data = request()->validate([
                    'fechaRealCierre' => 'nullable',
                    'numeroCambios' => 'nullable',
                    'porcentajeEjecutado' => 'nullable',
                    'cierre' => 'nullable'
                ]);


                $requerimiento->update($data);
                return redirect()->route('Requerimientos.show', ['requerimiento' => $requerimiento]);                 
            } else {
                $cambios +=1;
                $request->merge(['numeroCambios' => $cambios]);                
                $data = request()->validate([
                    'fechaRealCierre' => 'nullable',
                    'numeroCambios' => 'nullable',
                    'porcentajeEjecutado' => 'nullable',
                    'cierre' => 'nullable'
                ]);


                $requerimiento->update($data);
                return redirect()->route('Requerimientos.show', ['requerimiento' => $requerimiento]);                
            }
        }
        else {
                $data = request()->validate([
                    'fechaRealCierre' => 'nullable',
                    'numeroCambios' => 'nullable',
                    'porcentajeEjecutado' => 'nullable',
                    'cierre' => 'nullable'
                ]);
                $requerimiento->update($data);
                return redirect()->route('Requerimientos.show', ['requerimiento' => $requerimiento]);
        }            
    }

    public function terminado(Requerimiento $requerimiento)
    {
        return view('Requerimientos.terminado', compact('requerimiento'));        
    } 

    public function guardar(Request $request, Requerimiento $requerimiento)
    {
        $data = request()->validate([
            'cierre'=>'required'],
            ['cierre.required' => 'El texto de cierre es obligatorio']);

        $requerimiento->update($data);
        $data = [
            'estado' => 0,
            'porcentajeEjecutado' => 100,
            ];
        DB::table('requerimientos')->where('id', $requerimiento->id)->update($data);

        return redirect('requerimientos');   
    }              
}
