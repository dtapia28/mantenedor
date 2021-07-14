<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Requerimiento;
use App\Parametros;
use App\Resolutor;

class NotificaSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica requerimiento atrasado por SMS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $parametros = DB::table('parametros')->get();
        foreach($parametros as $parametro)
        {
            $cantidad = $parametro->dias_notify;
            $hoy = new DateTime();
            if($parametro->sms == 1){
                $resolutores = Resolutor::where([
                    ['rutEmpresa', $parametro->rutEmpresa]
                ])->get();
                foreach ($resolutores as $resolutor) {
                    $requerimientos = Requerimiento::where([
                        ['rutEmpresa', $parametro->rutEmpresa],
                        ['resolutor', $resolutor['id']],
                        ['estado', 1],
                        ['porcentajeEjecutado', '<', 100],
                    ])->get();
                    if(count($requerimientos)>0){
                        foreach ($requerimientos as $req) {
                            if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != "")
                            {
                                $fecha = new DateTime($req['fechaRealCierre']);
                                $variable = 'P'.strval($cantidad).'D';
                                $comparar = $fecha->sub(new DateInterval($variable));
                                $prueba_edit = $comparar->format('Y-m-d');
                                $hoy_edit = $hoy->format('Y-m-d');
                                $telefono = User::where([
                                    ['id', $resolutor['idUser']],
                                ])->first();
                                $telefono = $telefono['phone_number'];
                                $mensaje = "Te informamos que la fecha del req ".$req['id2']." está pronta a cumplirse, Kinchika";
                                if ($prueba_edit == $hoy_edit) {
                                    $data = [
                                        "account_id" => 3918841,
                                        "api_key" => "b2611cfa-a8b1-4dcc-9f5a-e31899d6a50f",
                                        'src_number'=> "13023650926",
                                        'dst_numbers'=> $telefono,
                                        'text'=> $mensaje                                        
                                    ];
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, "https://api.voximplant.com/platform_api/A2PSendSms/");
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($curl, CURLOPT_POST, 1);
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                                    $var = curl_exec($curl);
                                    curl_close($curl);
                                    dd($var);
                                }
                            } else {
                                $fecha = new DateTime($req['fechaCierre']);
                                $variable = 'P'.strval($cantidad).'D';
                                $comparar = $fecha->sub(new DateInterval($variable));
                                $prueba_edit = $comparar->format('Y-m-d');
                                $hoy_edit = $hoy->format('Y-m-d');
                                $telefono = User::where([
                                    ['id', $resolutor['idUser']],
                                ])->first();
                                $telefono = $telefono['phone_number'];
                                $mensaje = "Te informamos que la fecha del req ".$req['id2']." está pronta a cumplirse, Kinchika";
                                if ($prueba_edit == $hoy_edit) {
                                    $data = [
                                        "account_id" => 3918841,
                                        "api_key" => "b2611cfa-a8b1-4dcc-9f5a-e31899d6a50f",
                                        'src_number'=> "13023650926",
                                        'dst_numbers'=> $telefono,
                                        'text'=> $mensaje                                        
                                    ];
                                    $curl = curl_init();
                                    curl_setopt($curl, CURLOPT_URL, "https://api.voximplant.com/platform_api/A2PSendSms/");
                                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                                    curl_setopt($curl, CURLOPT_POST, 1);
                                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                                    $var = curl_exec($curl);
                                    curl_close($curl);
                                    dd($var);
                                }                               
                            }
                        }
                    }
                    
                }
            }

        }
    }
}
