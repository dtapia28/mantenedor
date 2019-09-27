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
use App\User;
use App\LogRequerimientos;
use App\Tarea;
use App\Notifications\NewReqResolutor;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use DateTime;
use DateInterval;
use App\Http\Controllers\Controller;
use Excel;
use Carbon\Carbon;

class RequerimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {

        $anidados = Anidado::all();

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        
        $request->user()->authorizeRoles(['solicitante', 'administrador', 'supervisor', 'resolutor']);

        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        if ($request->state != "") {
            $request->session()->put('state', $request->state);
        } else {
            $request->session()->put('state', "1");
        }

        if ($user[0]->nombre == "resolutor") {
            $res = Resolutor::where('idUser', $user[0]->idUser)->first();

            switch ($request->session()->get('state')) 
            {

                case '1':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', $request->session()->get('state')],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['resolutor', $res->id],
                ])->get();
                    break;
                case '0':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 2],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['resolutor', $res->id],
                ])->get();
                    break;  
                case '2':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 1],
                    ['porcentajeEjecutado', '>=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['resolutor', $res->id],
                ])->get();
                    break;
                case '3':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 1],
                    ['porcentajeEjecutado', '<=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['resolutor', $res->id],
                ])->get();
                    break;
            }
        }elseif ($user[0]->nombre == "solicitante") {
            $sol = Solicitante::where('idUser', $user[0]->idUser)->first();
            switch ($request->session()->get('state')) 
            {

                case '1':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', $request->session()->get('state')],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['idSolicitante', $sol->id],
                ])->get();
                    break;
                case '0':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 2],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                    ['idSolicitante', $sol->id],
                ])->get();
                    break;  
                case '2':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 1],
                    ['porcentajeEjecutado', '>=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
                case '3':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 1],
                    ['porcentajeEjecutado', '<=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
            }
        } else {
            switch ($request->session()->get('state')) 
            {

                case '1':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', $request->session()->get('state')],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
                case '0':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 2],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;  
                case '2':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 1],
                    ['porcentajeEjecutado', '>=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
                case '3':
                $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
                    ['estado', '=', 1],
                    ['porcentajeEjecutado', '<=', $request->valorN],
                    ['rutEmpresa', '=', auth()->user()->rutEmpresa],
                ])->get();
                    break;
            }            
        }          

/*        if ($request->session()->get('state') == 1)
        {
        	switch ($user[0]->nombre) {
        		case 'solicitante':
		            $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
		                ['estado', '=', $request->session()->get('state')],
		                ['idSolicitante', $user[0]->idUser],
		                ['rutEmpresa', '=', auth()->user()->rutEmpresa],
		            ])->get();        			
        			break;
        		case 'resolutor':		
		            $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
		                ['estado', '=', $request->session()->get('state')],
		                ['resolutor', $user[0]->idUser],
		                ['rutEmpresa', '=', auth()->user()->rutEmpresa],
		            ])->get(); 
		            break;        		
        		default:
		            $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
		                ['estado', '=', $request->session()->get('state')],
		                ['rutEmpresa', '=', auth()->user()->rutEmpresa],
		            ])->get(); 
        			break;
        	}

        } else
        {
        	switch ($user[0]->nombre) {
        		case 'solicitante':
		            $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
		                ['estado', '=', 2],
		                ['idSolicitante', $user[0]->idUser],
		                ['rutEmpresa', '=', auth()->user()->rutEmpresa],
		            ])->get();        			
        			break;
        		case 'resolutor':		
		            $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
		                ['estado', '=', 2],
		                ['resolutor', $user[0]->idUser],
		                ['rutEmpresa', '=', auth()->user()->rutEmpresa],
		            ])->get(); 
		            break;        		
        		default:
		            $requerimientos = Requerimiento::orderBy('fechaSolicitud', 'desc')->where([
		                ['estado', '=', 2],
		                ['rutEmpresa', '=', auth()->user()->rutEmpresa],
		            ])->get(); 
        			break;
        	}           
        }*/
        $valor = 1;
        if ($request->session()->get('state') == 1) {
            $valor = 1;
        }else {
            $valor = 0;
        }

        return view('Requerimientos.index', compact('requerimientos', 'resolutors', 'teams', 'valor', 'user', 'anidados'));

    }

    public function getResolutors(Request $request)
    {
        if ($request->ajax()) {
            $resolutors = Resolutor::where('idTeam', $request->id_team)->get();
            foreach ($resolutors as $resolutor) {
                $resolutorArray[$resolutor->id] = $resolutor->nombreResolutor;
            }

            return response()->json($resolutorArray);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();

        auth()->user()->authorizeRoles(['administrador', 'solicitante']);    

        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreResolutor')->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('namePriority')->get();
        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->orderBy('nombreSolicitante')->get();

        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();

        return view('Requerimientos.create', compact('resolutors', 'priorities', 'solicitantes', 'user', 'teams'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        auth()->user()->authorizeRoles(['administrador', 'solicitante']);
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        $sol = Solicitante::where('idUser', $user[0]->idUser)->first();

            $data = request()->validate([
                'textoRequerimiento' => 'required',
                'fechaEmail' => 'required',
                'fechaSolicitud' => 'required',
                'fechaCierre' => 'nullable',
                'fechaRealCierre' => 'nullable',
                'numeroCambios' => 'nullable',
                'porcentajeEjecutado' => 'nullable',
                'cierre' => 'nullable',
                'idPrioridad' => 'required',
                'textAvance' => 'nullable',
                'idSolicitante' => 'nullable',
                'idResolutor' => 'required'],
                ['nombreResolutor.required' => 'El campo nombre es obligatorio'],
                ['fechaEmail.required' => 'La fecha de email es obligatoria'],
                ['fechaSolicitud' => 'La fecha de solicitud es obligatoria'],
                ['fechaCierre' => 'La fecha de cierre es obligatoria']);

        if ($user[0]->nombre == 'solicitante') {
            $data['idSolicitante'] = $sol->id;
        }

        if ($data['fechaCierre'] == null) {
            $variable = new DateTime($data['fechaSolicitud']);
            $intervalo = new DateInterval('P1M');
            $variable->add($intervalo);
            $data['fechaCierre'] = $variable->format('Y-m-d');
        } else {
            $variable = new DateTime($data['fechaCierre']);
            $data['fechaCierre'] = $variable->format('Y-m-d');
        }

        $variable = new DateTime($data['fechaSolicitud']);
        $data['fechaSolicitud'] = $variable->format('Y-m-d');

        $variable = new DateTime($data['fechaEmail']);
        $data['fechaEmail'] = $variable->format('Y-m-d');

        if (isset($data['fechaRealCierre'])) {
        $variable = new DateTime($data['fechaRealCierre']);
        $data['fechaRealCierre'] = $variable->format('Y-m-d'); 
        }
        
        $fechaSoli = new DateTime($data['fechaSolicitud']);
        $fechaCie = new DateTime($data['fechaCierre']);

        if ($fechaCie->getTimestamp() >= $fechaSoli->getTimestamp()) 
        {
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
                
                $conteo = 1;
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

                $var = "RQ-".$team[0]->id2."-".$conteoA;
              

            Requerimiento::create([
                'textoRequerimiento' => $data['textoRequerimiento'],            
                'fechaEmail' => $data['fechaEmail'],
                'fechaSolicitud' => $data['fechaSolicitud'],
                'fechaCierre' => $data['fechaCierre'],
                'idSolicitante' => $data['idSolicitante'],
                'idPrioridad' => $data['idPrioridad'],
                'resolutor' => $data['idResolutor'],
                'rutEmpresa' => auth()->user()->rutEmpresa,
                'id2' => "RQ-".$team[0]->id2."-".$conteoA,
            ]);

            $requerimiento = Requerimiento::where('id2', $var)->first();
            $resolutor = Resolutor::where('id', $requerimiento->resolutor)->first();
            $obj = new \stdClass();
            $obj->idReq = $requerimiento->id2;
            $obj->id = $requerimiento->id;
            $obj->sol = $requerimiento->textoRequerimiento;
            $obj->nombre = $resolutor->nombreResolutor;

            $recep = $resolutor->email;
        
            Notification::route('mail','dtapia1025@gmail.com')->notify(new NewReqResolutor($obj));         


            $conteo = 1;

            $req = Requerimiento::where('textoRequerimiento', $data['textoRequerimiento'])->get();

            $user = User::where([
                ['name', auth()->user()->name],
                ['rutEmpresa', auth()->user()->rutEmpresa],
            ])->get();

            LogRequerimientos::create([
                'idRequerimiento' => $req[0]->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Creación',
            ]);

            if ($data['textAvance'] != null) {
                $guardado = Requerimiento::where([
                    ['textoRequerimiento', $data['textoRequerimiento']],
                    ['fechaEmail', $data['fechaEmail']],
                    ['fechaSolicitud', $data['fechaSolicitud']],
                ])->first();

                Avance::create([
                    'textAvance' => $data['textAvance'],
                    'fechaAvance' => Carbon::now(),
                    'idRequerimiento' => $guardado->id
                ]);
            }



            return redirect('requerimientos');

        }else 
        {
            return back()->with('msj', 'La fecha de cierre del requerimiento debe ser mayor a la fecha de solicitud');
        }

        $requerimiento = Requerimiento::where('id2', '"RQ-".$team[0]->id2."-".$conteoA')->get();

        Mail::to($receivers)->send(new NewReqMail());   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function show(Requerimiento $requerimiento)
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();
        auth()->user()->authorizeRoles(['administrador', 'solicitante', 'resolutor', 'supervisor']);          
        $tareas = Tarea::where('idRequerimiento', $requerimiento->id)->get();
        $avances = Avance::where('idRequerimiento', $requerimiento->id)->latest('created_at')->paginate(5);
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $teams = Team::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosAnidadosLista = Anidado::where('idRequerimientoBase', $requerimiento->id)->get();
        $requerimientos = Requerimiento::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $requerimientosAnidados = [];
        foreach ($requerimientosAnidadosLista as $requerimiento1)
        {
            foreach ($requerimientos as $requerimientos1) 
            {
                if ($requerimientos1->id == $requerimiento1->idRequerimientoAnexo) 
                {
                    $requerimientosAnidados[] = $requerimientos1;
                }
            }
        }

            define("FECHACIERRE", "$requerimiento->fechaCierre");
            define("FECHASOLICITUD", "$requerimiento->fechaSolicitud");
            define("FECHAREALCIERRE", "$requerimiento->fechaRealCierre");
            $fechaCierre = new DateTime(FECHACIERRE);
            $restantes = 0;                   

        if ($requerimiento->fechaCierre == "9999-12-31 00:00:00" or $requerimiento->fechaSolicitud == $requerimiento->fechaCierre) 
        {
            $hastaCierre = 1;
            $hastaHoy = 0;
            $restantes = 0;
            $excedidos = 0;
            $hoy = new DateTime();

        } 
        else 
        {
 
            $hastaCierre = 0;
            $variable = new DateTime(FECHASOLICITUD);

            while ($variable->getTimestamp() < $fechaCierre->getTimestamp()) 
            {
                if ($variable->format('l') == 'Saturday' or $variable->format('l') == 'Sunday') 
                {
                    $variable->modify("+1 days");               
                }else
                {
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
                if ($requerimiento->fechaRealCierre != null) 
                {
                    $cierre = new DateTime(FECHAREALCIERRE);
                    $variable = new DateTime(FECHASOLICITUD);             
                    while ($variable->getTimestamp() <= $cierre->getTimestamp()) {
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

            if ($comienzo>$fechaFinal or $requerimiento->cierre != null) 
            {

                $restantes = 0;    

            } else 
            {
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

            if ($requerimiento->estado == 1) 
            {
                if ($restantes>0) 
                {
                    $excedidos = 0;
                } else
                {
                    
                    $cierreReal = new DateTime(FECHAREALCIERRE);
                    $cierre = new DateTime(FECHACIERRE);
                    $ahora = new DateTime();

                        while ($cierre->getTimestamp() < $ahora->getTimestamp()) 
                        {
                            if ($cierre->format('l') == 'Saturday' or $cierre->format('l') == 'Sunday') 
                            {
                                $cierre->modify("+1 days");
                            } else 
                            {
                                $excedidos++;
                                $cierre->modify("+1 days");
                            }
                        }
                }
            } else
            {
                if ($requerimiento->fechaSolicitud == $requerimiento->fechaRealCierre) 
                {
                    $excedidos = 0;
                } else
                {
                    $cierreReal = new DateTime(FECHAREALCIERRE);
                    $cierre = new DateTime(FECHACIERRE);                    
                    while ($cierre->getTimestamp() < $cierreReal->getTimestamp()) 
                    {
                        if ($cierre->format('l') == 'Saturday' or $cierre->format('l') == 'Sunday') 
                        {
                            $cierre->modify("+1 days");
                        } else 
                        {
                            $excedidos++;
                            $cierre->modify("+1 days");
                        }
                    }                    
                }
            }
        }        


        return view('Requerimientos.show', compact('user','requerimiento', 'resolutors', 'priorities', 'avances', 'teams', 'hastaCierre', 'hastaHoy', 'restantes', 'hoy', 'fechaCierre', 'excedidos', 'requerimientosAnidados', 'tareas', 'requerimientos'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Requerimiento  $requerimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(Requerimiento $requerimiento)
    {

        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        $fechita = str_split($requerimiento->fechaCierre);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $cierre = implode("", $fechota);

        $fechita = str_split($requerimiento->fechaSolicitud);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $solicitud = implode("", $fechota);

        $solicitantes = Solicitante::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $solicitanteEspecifico = Solicitante::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->idSolicitante],
        ])->get();
        $prioridadEspecifica = Priority::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->idPrioridad],
        ])->get();
        $resolutorEspecifico = Resolutor::where([
            ['rutEmpresa', auth()->user()->rutEmpresa],
            ['id', $requerimiento->resolutor],
        ])->get();
        $priorities = Priority::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $resolutors = Resolutor::where('rutEmpresa', auth()->user()->rutEmpresa)->get();
        $fechaCierre = new DateTime($requerimiento->fechaCierre);          

        return view('Requerimientos.edit', compact('requerimiento', 'solicitantes', 'priorities', 'resolutors', 'fechaCierre', 'cierre', 'solicitud', 'solicitanteEspecifico', 'prioridadEspecifica', 'resolutorEspecifico', 'user'));        
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
            'fechaCierre' => 'nullable',
            'textAvance' => 'nullable',            
            'fechaSolicitud' => 'nullable',
        ]);

        $data['idEmpresa'] = auth()->user()->rutEmpresa;

        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();

        if ($data['textoRequerimiento'] != $requerimiento->textoRequerimiento) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'texto Requerimiento',
            ]);             
        }

        if ($data['idSolicitante'] != $requerimiento->idSolicitante) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'id solicitante',
            ]);              
        }

        if ($data['idPrioridad'] != $requerimiento->idPrioridad) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'id prioridad',
            ]);             
        }

        if ($data['idResolutor'] != $requerimiento->resolutor) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'id resolutor',
            ]);
        } 

        $fechita = str_split($requerimiento->fechaCierre);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $cierre = implode("", $fechota);                

        if ($data['fechaCierre'] != $cierre) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'fecha cierre',
            ]);
        }  

        $fechita = str_split($requerimiento->fechaSolicitud);
        $fechota = [];
        for ($i=0; $i < 10; $i++) { 
            $b = strtoupper($fechita[$i]);
            array_push($fechota, $b);
        }
        $solicitud = implode("", $fechota);        

        if ($data['fechaSolicitud'] != $solicitud) {
            LogRequerimientos::create([
                'idRequerimiento' => $requerimiento->id,
                'idUsuario' => $user[0]->id,
                'tipo' => 'Edición',
                'campo' => 'fecha solicitud',
            ]);
        }                          
              
        $requerimiento->update($data);

        if ($data['textAvance'] != "") {

                Avance::create([
                    'textAvance' => $data['textAvance'],
                    'fechaAvance' => Carbon::now(),
                    'idRequerimiento' => $requerimiento->id
                ]);            
            
        }
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
        $user = User::where([
            ['name', auth()->user()->name],
            ['rutEmpresa', auth()->user()->rutEmpresa],
        ])->get();        
        LogRequerimientos::create([
            'idRequerimiento' => $requerimiento->id,
            'idUsuario' => $user[0]->id,
            'tipo' => 'Eliminación',
            'campo' => '',
        ]);       
        $data = [
            'estado' => 0,
        ];
        DB::table('requerimientos')->where('id', $requerimiento->id)->update($data);

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

                $user = User::where([
                    ['name', auth()->user()->name],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();        
                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->id,
                    'tipo' => 'Crea',
                    'campo' => 'Avance',
                ]);  

                $requerimiento->update($data);
                return redirect()->route('Requerimientos.show', ['requerimiento' => $requerimiento]);                
            }
        }
        else 
        {

                $user = User::where([
                    ['name', auth()->user()->name],
                    ['rutEmpresa', auth()->user()->rutEmpresa],
                ])->get();        
                LogRequerimientos::create([
                    'idRequerimiento' => $requerimiento->id,
                    'idUsuario' => $user[0]->id,
                    'tipo' => 'Crea',
                    'campo' => 'Avance',
                ]);  

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
        $user = DB::table('usuarios')->where('idUser', auth()->user()->id)->get();        
        return view('Requerimientos.terminado', compact('requerimiento', 'user'));        
    } 

    public function guardar(Request $request, Requerimiento $requerimiento)
    {
        $data = request()->validate([
            'cierre'=>'required',
            'fechaRealCierre' => 'nullable'],
            ['cierre.required' => 'El texto de cierre es obligatorio']);
       
       if (empty($data['fechaRealCierre'])) {
        $data = [
            'estado' => 2,
            'porcentajeEjecutado' => 100,
            'cierre' => $data['cierre'],
        ];           
       } else {
        $data = [
            'estado' => 2,
            'porcentajeEjecutado' => 100,
            'cierre' => $data['cierre'],
            'fechaRealCierre' => $data['fechaRealCierre'],
        ];         
       }


        $requerimiento->update($data);        

        //DB::table('requerimientos')->where('id', $requerimiento->id)->update($data);        

        return redirect('requerimientos');  
    }

    public function prueba(){
        $variable = $_POST['texto'];
    }              
}
