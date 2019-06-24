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
        $requerimientos = Requerimiento::where([
            ['estado', '=', $request->state],
            ['rutEmpresa', '=', auth()->user()->rutEmpresa],
        ])->simplePaginate(10);
        $valor = 1;
        if ($request->state == 1) {
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
    
        $resolutors = Resolutor::all();
        $priorities = Priority::all();
        $solicitantes = Solicitante::all();

        return view('Requerimientos.create', compact('resolutors', 'priorities', 'solicitantes'));        
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
            'idResolutor' => 'required'],
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
            'rutEmpresa' => auth()->user()->rutEmpresa,
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
        $avances = Avance::latest('created_at')->paginate(9);
        $resolutors = Resolutor::all();
        $priorities = Priority::all();
        $teams = Team::all();
        define("FECHACIERRE", "$requerimiento->fechaCierre");
        define("FECHASOLICITUD", "$requerimiento->fechaSolicitud");
        define("FECHAREALCIERRE", "$requerimiento->fechaRealCierre");
        $fechaCierre = new DateTime(FECHACIERRE);
        $hastaCierre = 0;
        $variable = new DateTime(FECHASOLICITUD);

        while ($variable->getTimestamp() < $fechaCierre->getTimestamp()) {
            if ($variable->format('l') == 'Saturday' or $variable->format('l') == 'Sunday') {
                $variable->modify("+1 days");               
            }else{
                $hastaCierre++;
                $variable->modify("+1 days");                       
            }
        }

        $hastaHoy = -1;
        $hoy = new DateTime();
        if ($requerimiento->estado == 1) 
        {

            $variable = new DateTime(FECHASOLICITUD);         
            while ($variable->getTimestamp() < $hoy->getTimestamp()) {
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
                $variable = new DateTime(FECHASOLICITUD);             
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
                $variable = new DateTime(FECHASOLICITUD);               
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
        $fechaFinal = new DateTime(FECHACIERRE);

        if ($comienzo>$fechaFinal) 
        {

            $restantes = 0;    

        } else {
            while ($comienzo->getTimestamp() < $fechaFinal->getTimestamp()){

                if ($comienzo->format('l') == 'Saturday' or $comienzo->format('l') == 'Sunday'){
                    $comienzo->modify("+1 days");                         
                } else {
                    $restantes++;
                    $comienzo->modify("+1 days");                        
                }
            }
        }

        $excedidos = -1;

        if ($restantes>0) {
            $excedidos = 0;
        } else{

            $cierreReal = new DateTime(FECHAREALCIERRE);
            $cierre = new DateTime(FECHACIERRE);
            $ahora = new DateTime();
            if ($ahora->getTimestamp() < $cierreReal->getTimestamp()) {
                while ($cierre->getTimestamp() < $ahora->getTimestamp()) {

                    if ($cierre->format('l') == 'Saturday' or $comienzo->format('l') == 'Sunday') {

                        $cierre->modify("+1 days");
                    } else {
                        $excedidos++;
                        $cierre->modify("+1 days");
                    }
                }
            } else {
                while ($cierre->getTimestamp() < $cierreReal->getTimestamp()) {

                    if ($cierre->format('l') == 'Saturday' or $comienzo->format('l') == 'Sunday') {

                        $cierre->modify("+1 days");
                    } else {
                        $excedidos++;
                        $cierre->modify("+1 days");
                    }
                }
            }
        }


        return view('Requerimientos.show', compact('requerimiento', 'resolutors', 'priorities', 'avances', 'teams', 'hastaCierre', 'hastaHoy', 'restantes', 'hoy', 'fechaCierre', 'excedidos'));        
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
            'idPrioridad' => 'nullable',
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
        $avances = Avance::where('idRequerimientos', $requerimiento->id)->get();
        foreach ($avances as $avance) {
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
