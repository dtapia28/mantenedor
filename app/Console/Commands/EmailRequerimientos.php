<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Requerimiento;
use App\Resolutor;
use DateTime;
use App\Notifications\RequerimientoEmail;
use Illuminate\Support\Facades\Notification;

class EmailRequerimientos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviarEmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'enviar correo de requerimientos por vencer a resolutores';

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
        $requerimientos = Requerimiento::all();
        $resolutores = Resolutor::all();
        foreach ($requerimientos as $requerimiento) {
            define("FECHACIERRE", "$requerimiento->fechaCierre");
            $variable = new DateTime(FECHACIERRE);
            $hoy = new DateTime();
            $hastaCierre = 0;          
            while ($hoy->getTimestamp() < $variable->getTimestamp()) 
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
            if ($hastaCierre <= 5) {
                foreach ($$resolutores as $resolutor) {
                    if ($requerimiento->resolutor == $resolutor->id) {
                        $obj = new \stdClass();
                        $obj->idReq = $requerimiento->id2;
                        $obj->id = $requerimiento->id;
                        $obj->dias = $hastaCierre;
                        $obj->nombre = $resolutor->nombreResolutor;

                        $recep = $resolutor->email;
                    
                        Notification::route('mail','dtapia1025@gmail.com')->notify(new RequerimientoEmail($obj));                    
                    }
                }
            }    
        }
    }
}
