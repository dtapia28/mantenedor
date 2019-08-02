<?php

namespace App\Http\Controllers;

use App\Requerimiento;
use App\Empresa;
use App\Resolutor;
use App\Priority;
use App\Solicitante;
use App\Avance;
use App\Team;
use App\Anidado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use DateTime;
use DateInterval;
use App\Exports\RequerimientosExport;
use App\Http\Controllers\Controller;
use Excel;

class RequerimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        if ($request->state != "") {
            $request->session()->put('state', $request->state);
        } else {
            $request->session()->put('state', 1);
        }

        $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
            ['estado', '=', $request->session()->get('state')],
            ['rutEmpresa', '=', auth()->user()->rutEmpresa],
        ])->get();
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
    
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreResolutor')->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('namePriority')->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();

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

        $fechaSoli = new DateTime($data['fechaSolicitud']);
        $fechaCie = new DateTime($data['fechaCierre']);

        if ($fechaCie->getTimestamp() > $fechaSoli->getTimestamp()) {

            $resolutor = Resolutor::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['id', $data['idResolutor']],
            ])->get();

            $team = Team::where([
                ['rutEmpresa', auth()->user()->rutEmpresa],
                ['id',$resolutor[0]->idTeam],
            ])->get(); 

            $resolutors = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();

            $requerimientos = Requerimiento::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();
            
            $conteo = 0;
            foreach ($resolutors as $resolutor) {
                if ($resolutor->idTeam == $team[0]->id) {
                    foreach ($requerimientos as $requerimiento) {
                        if ($requerimiento->resolutor == $resolutor->id) {
                            $conteo++;
                        }
                    }
                }
            }

            if ($conteo < 10) {
                $conteoA = "00".$conteo;
            } elseif ($conteo >= 10 and $conteo <= 99){
                $conteoA = "0".$conteo;
            } else {
                $conteoA = $conteo;
            }

        Requerimiento::create([
            'textoRequerimiento' => $data['textoRequerimiento'],            
            'fechaEmail' => $data['fechaEmail'],
            'fechaSolicitud' => $data['fechaSolicitud'],
            'fechaCierre' => $data['fechaCierre'],
            'idSolicitante' => $data['idSolicitante'],
            'idPrioridad' => $data['idPrioridad'],
            'resolutor' => $data['idResolutor'],
            'rutEmpresa' => auth()->user()->rutEmpresa,
            'id2' => "RQ".$team[0]->id2.$conteoA,
        ]);

        $conteo = 0;

        return redirect('requerimientos');

        }else {
            return back()->with('msj', 'La fecha de cierre del requerimiento debe ser mayor a la fecha de solicitud');
        }      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Requerimiento $requerimiento)
    {
        $avances = Avance::where('idRequerimiento', $requerimiento->id)->latest('created_at')->paginate(5);
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosAnidadosLista = Anidado::where('idRequerimientoBase', $requerimiento->id)->get();
        $requerimientos = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosAnidados = [];
        foreach ($requerimientosAnidadosLista as $requerimiento1) {
            foreach ($requerimientos as $requerimientos1) {
                if ($requerimientos1->id == $requerimiento1->idRequerimientoAnexo) {
                    $requerimientosAnidados[] = $requerimientos1;
                }
            }
        }

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


        return view('Requerimientos.show', compact('requerimiento', 'resolutors', 'priorities', 'avances', 'teams', 'hastaCierre', 'hastaHoy', 'restantes', 'hoy', 'fechaCierre', 'excedidos', 'requerimientosAnidados'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento)
    {
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Requerimientos.edit', compact('requerimiento', 'solicitantes', 'priorities', 'resolutors'));        
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
            'fechaCierre' => 'nullable'
        ]);
        $data['idEmpresa'] = auth()->user()->rutEmpresa;
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
