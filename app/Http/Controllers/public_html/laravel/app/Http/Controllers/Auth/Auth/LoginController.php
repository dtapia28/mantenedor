<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Requerimiento;
use App\Resolutor;
use App\User;
use App\Envio_email;
use DateTime;
use App\Notifications\RequerimientoEmail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers; 

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/requerimientos';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $envios = Envio_email::all();
        $dia = new DateTime();
        $dia = $dia->format('Y-m-d');
        $enviado = false;
        foreach ($envios as $envio) {
            $fenvio = new DateTime($envio->fecha);
            $fenvio = $fenvio->format('Y-m-d');
            if ($fenvio == $dia) {
                $enviado = true;
            }
        }



        if ($enviado == false) {
            $fecha = new DateTime();
            $fecha = $fecha->format('Y-m-d');
            Envio_email::create([
                'fecha' => $fecha,
                'descripcion' => 'proceso ejecutado',
            ]);            
            $requerimientos = Requerimiento::where([
                ['estado', 1],
                ['aprobacion', 3],
            ])->get();
            $resolutores = Resolutor::all();
            foreach ($requerimientos as $requerimiento) {
                if($requerimiento->fechaRealCierre != null and $requerimiento->fechaRealCierre != "9999-12-31 00:00:00"){
                    $variable = new DateTime($requerimiento->fechaRealCierre);
                    $hoy = new DateTime();
                    $hastaCierre = 0;          
                    while ($hoy->getTimestamp() < $variable->getTimestamp()) 
                    {
                        if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                        {
                            $hoy->modify("+1 days");               
                        }else
                        {
                            $hastaCierre++;
                            $hoy->modify("+1 days");                       
                        }
                    }                   
                } elseif($requerimiento->fechaCierre != "9999-12-31 00:00:00" and $requerimiento->fechaRealCierre == null) {
                    $variable = new DateTime($requerimiento->fechaCierre);
                    $hoy = new DateTime();
                    $hastaCierre = 0;          
                    while ($hoy->getTimestamp() < $variable->getTimestamp()) 
                    {
                        if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                        {
                            $hoy->modify("+1 days");               
                        }else
                        {
                            $hastaCierre++;
                            $hoy->modify("+1 days");                       
                        }
                    }                    
                }    

                if ($requerimiento->fechaRealCierre != null and $requerimiento->fechaRealCierre != "9999-12-31 00:00:00") {
                    $variable = new DateTime($requerimiento->fechaRealCierre);
                    $hoy = new DateTime();
                    $hastaCierre = 0;          
                    while ($hoy->getTimestamp() < $variable->getTimestamp()) 
                    {
                        if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                        {
                            $hoy->modify("+1 days");               
                        }else
                        {
                            $hastaCierre++;
                            $hoy->modify("+1 days");                       
                        }
                    }
                    if ($hastaCierre <= 5 and $hastaCierre > 0) {
                        foreach ($resolutores as $resolutor) {
                            if ($requerimiento->resolutor == $resolutor->id) {
                                $obj = new \stdClass();
                                $obj->idReq = $requerimiento->id2;
                                $obj->id = $requerimiento->id;
                                $obj->sol = $requerimiento->textoRequerimiento;
                                $obj->dias = $hastaCierre;
                                $obj->nombre = $resolutor->nombreResolutor;
                                $obj->fecha = $requerimiento->fechaRealCierre;
                                $obj->porcentaje = $requerimiento->porcentajeEjecutado;

                                $recep = $resolutor->email;                        

                                Notification::route('mail',$recep)->notify(new RequerimientoEmail($obj));

                            }
                        }
                    }
                } else 
                {
                    if ($requerimiento->fechaCierre != "9999-12-31 00:00:00" and $requerimiento->fechaRealCierre == null){

                        $variable = new DateTime($requerimiento->fechaCierre);
                        $hoy = new DateTime();
                        $hastaCierre = 0;          
                        while ($hoy->getTimestamp() < $variable->getTimestamp()) 
                        {
                            if ($hoy->format('l') == 'Saturday' or $hoy->format('l') == 'Sunday') 
                            {
                                $hoy->modify("+1 days");               
                            }else
                            {
                                $hastaCierre++;
                                $hoy->modify("+1 days");                       
                            }
                        }
                        if ($hastaCierre <= 5 and $hastaCierre > 0) {
                            foreach ($resolutores as $resolutor) {
                                if ($requerimiento->resolutor == $resolutor->id) {
                                    $obj = new \stdClass();
                                    $obj->idReq = $requerimiento->id2;
                                    $obj->id = $requerimiento->id;
                                    $obj->sol = $requerimiento->textoRequerimiento;
                                    $obj->dias = $hastaCierre;
                                    $obj->nombre = $resolutor->nombreResolutor;
                                    $obj->fecha = $requerimiento->fechaRealCierre;
                                    $obj->porcentaje = $requerimiento->porcentajeEjecutado;                                    

                                    $recep = $resolutor->email;                        

                                    Notification::route('mail',$recep)->notify(new RequerimientoEmail($obj));

                                }
                            }
                        }
                    }
                }    
            }         
            $this->middleware('guest')->except('logout');
        } else {
            $this->middleware('guest')->except('logout');    
        }                 
    }
    
    public function redirectToProvider()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }
    
    public function handleProviderCallback()
    {
        try {
            $user = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login');
        }
        // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
        } else {
            \Illuminate\Support\Facades\Cache::put('nombre', $user->user['given_name'],5);
            \Illuminate\Support\Facades\Cache::put('apellido', $user->user['family_name'],5);
            \Illuminate\Support\Facades\Cache::put('mail', $user->email);
            return redirect()->to('/register');
        }
        return redirect()->to('/home');
    }    
}
