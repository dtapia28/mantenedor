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

class WhatsmsApi 
            {
                var $apikey;
                    
                public function setApiKey($apikey){
                    $this->apikey = $apikey;
                }
                    
                public function sendSms($phone, $message){
                    if(empty($phone)){
                        return array("status" => false, "message" => "example -> api->sendSms('+5555555555','message'); ");
                    }
                        
                    if(empty($message)){
                        return array("status" => false, "message" => "example -> api->sendSms('+5555555555','message'); ");
                    }
                        
                    if(!is_string($phone) || !is_string($message)){
                        return array("status" => false, "message" => "example -> api->sendSms('string','string'); ");
                    }
                        
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://whatsmsapi.com/api/send_sms',
                        CURLOPT_USERAGENT => 'WhatsmsApi',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => array(
                            "phone" => $phone,
                            "text" => $message
                        ),
                        CURLOPT_HTTPHEADER => array(
                            "x-api-key: $this->apikey"
                        )
                    ));
                    $resp = curl_exec($curl);
                    if(!$resp){
                        $response = array(
                            "phones" => array(
                                $phone
                            ), 
                            "text" => $message,
                            "sms" => array(
                                ((object) array(
                                    "idsms" => -1,
                                    "success" => false,
                                    "message" => "Error al ejecutar la operación"
                                ))
                            )
                        );
                        return $response;
                    }else{
                        curl_close($curl);
                        return json_decode($resp);
                    }
                }
                    
            }

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
            $requerimientos = Requerimiento::where('estado', 1)->get();
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
                    if($hastaCierre <= 3 and $hastaCierre > 0)
                    {
                        if ($requerimiento->msj_whats == 0){
                            $link = "http://app.kinchika.com/requerimientos/".$requerimiento->id;
                            $whatsmsapi = new WhatsmsApi();
                            $whatsmsapi->setApiKey("5e2edfe1aa0f9");
                            $whatsmsapi->sendSms("56953551286", "Hola soy Kinchika y te estoy enviando este mensaje porque el requerimiento ".$requerimiento->id2." está a 3 días de vencer.\n Puedes ver el detalle del requerimiento en el siguiente link: ".$link." .\n");
                            $data['msj_whats'] = 1;
                            $requerimiento->update($data);
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
                    if($hastaCierre <= 3 and $hastaCierre > 0)
                    {
                        if ($requerimiento->msj_whats == 0){
                            $link = "http://app.kinchika.com/requerimientos/".$requerimiento->id;
                            $whatsmsapi = new WhatsmsApi();
                            $whatsmsapi->setApiKey("5e2edfe1aa0f9");
                            $whatsmsapi->sendSms("56953551286", "Hola soy Kinchika y te estoy enviando este mensaje porque el requerimiento ".$requerimiento->id2." está a 3 días de vencer.\n Puedes ver el detalle del requerimiento en el siguiente link: ".$link." .\n");
                            $data['msj_whats'] = 1;
                            $requerimiento->update($data);   
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

                                Notification::route('mail','dtapia1025@gmail.com')->notify(new RequerimientoEmail($obj));

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

                                    Notification::route('mail','dtapia1025@gmail.com')->notify(new RequerimientoEmail($obj));

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
