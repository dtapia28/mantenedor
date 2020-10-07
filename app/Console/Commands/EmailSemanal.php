<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Resolutor;
use App\Requerimiento;
use DateTime;
use DateInterval;
use App\Mail\EnviaEmailResumen;

class EmailSemanal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:semanal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío semanal de detalle de requerimientos a cada resolutor';

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
        $resolutores = Resolutor::where([
         ['rutEmpresa', '90413000-1'],
         ['estado', 1],
        ])->get();
        
        $lunes = date("d-m-Y h:i", strtotime("-13 hours"));
        $lunes_date = new DateTime($lunes);
        $martes = date("d-m-Y h:i", strtotime("+1 day"));
        $martes_date = new DateTime($martes);
        $miercoles = date('d-m-Y h:i', strtotime("+2 day"));
        $miercoles_date = new DateTime($miercoles);
        $jueves = date("d-m-Y h:i", strtotime("+3 day"));
        $jueves_date = new DateTime($jueves);
        $viernes = date("d-m-Y h:i", strtotime("+4 day"));
        $viernes_date = new DateTime($viernes);
        $viernes_final = new DateTime(date("d-m-Y h:i", strtotime("+8 hours")));
        
        
        foreach ($resolutores as $resolutor) {
            
            $vencidos_lunes = [];
            $vencidos_martes = [];
            $vencidos_miercoles = [];
            $vencidos_jueves = [];
            $vencidos_viernes = [];
            $cantidad_total = 0;
            
            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado', '<', 100],
            ])->get();
            
            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $lunes_date and $fecha < $martes_date){
                        $vencidos_lunes[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $lunes_date and $fecha < $martes_date)
                   {
                       $vencidos_lunes[] = $req;
                       $cantidad_total++;
                   }
                }
            }
            
            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $martes_date and $fecha < $miercoles_date){
                        $vencidos_martes[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $martes_date and $fecha < $miercoles_date)
                   {
                       $vencidos_martes[] = $req;
                       $cantidad_total++;
                   }
                }
            }

            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $miercoles_date and $fecha < $jueves_date){
                        $vencidos_miercoles[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $miercoles_date and $fecha < $jueves_date)
                   {
                       $vencidos_miercoles[] = $req;
                       $cantidad_total++;
                   }
                }
            }

            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $jueves_date and $fecha < $viernes_date){
                        $vencidos_jueves[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $jueves_date and $fecha < $viernes_date)
                   {
                       $vencidos_jueves[] = $req;
                       $cantidad_total++;
                   }
                }
            }

            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $viernes_date and $fecha < $viernes_final){
                        $vencidos_viernes[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $viernes_date and $fecha < $viernes_final)
                   {
                       $vencidos_viernes[] = $req;
                       $cantidad_total++;
                   }
                }
            }  
            
            
            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado', null],
            ])->get();
            
            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $lunes_date and $fecha < $martes_date){
                        $vencidos_lunes[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $lunes_date and $fecha < $martes_date)
                   {
                       $vencidos_lunes[] = $req;
                       $cantidad_total++;
                   }
                }
            }
            
            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $martes_date and $fecha < $miercoles_date){
                        $vencidos_martes[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $martes_date and $fecha < $miercoles_date)
                   {
                       $vencidos_martes[] = $req;
                       $cantidad_total++;
                   }
                }
            }

            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $miercoles_date and $fecha < $jueves_date){
                        $vencidos_miercoles[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $miercoles_date and $fecha < $jueves_date)
                   {
                       $vencidos_miercoles[] = $req;
                       $cantidad_total++;
                   }
                }
            }

            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $jueves_date and $fecha < $viernes_date){
                        $vencidos_jueves[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $jueves_date and $fecha < $viernes_date)
                   {
                       $vencidos_jueves[] = $req;
                       $cantidad_total++;
                   }
                }
            }

            foreach ($requerimientos_resolutor as $req) {
                if($req['fechaRealCierre'] != null and $req['fechaRealCierre'] != ""){
                    $fecha = new DateTime($req['fechaRealCierre']);
                    if($fecha > $viernes_date and $fecha < $viernes_final){
                        $vencidos_viernes[] = $req;
                        $cantidad_total++;
                    }
                } else {
                    $fecha = new DateTime($req['fechaCierre']);
                   if($fecha > $viernes_date and $fecha < $viernes_final)
                   {
                       $vencidos_viernes[] = $req;
                       $cantidad_total++;
                   }
                }
            } 
            
            $valores['vencidos_lunes']=$vencidos_lunes;
            $valores['vencidos_martes']=$vencidos_martes;
            $valores['vencidos_miercoles']=$vencidos_miercoles;
            $valores['vencidos_jueves'] = $vencidos_jueves;
            $valores['vencidos_viernes'] = $vencidos_viernes;
            $valores['nombre_resolutor'] = $resolutor['nombreResolutor'];
            
//            if(count($valores['vencidos_lunes'])>0 or count($valores['vencidos_martes'])>0 or
//                count($valores['vencidos_miercoles'])>0 or count($valores['vencidos_jueves'])>0 or
//                count($valores['vencidos_viernes'])>0)
//            {
               Mail::to('dtapia@itconsultants.cl')->send(new EnviaEmailResumen($valores));
               
               //EmailSemanal::enviar_voximplant('56953551286', $resolutor['nombreResolutor'], $cantidad_total);
//            }  
        }
    }
     
}
