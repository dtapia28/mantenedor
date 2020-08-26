<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Team;
use App\Resolutor;
use App\Requerimiento;
use App\Mail\SendMailable;
use DateTime;
use DateInterval;


class EnvioReporteSemanal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'envio:reporte';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía reporte de RQ';

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
        $equipos = Team::where('rutEmpresa', '90413000-1')->get();
        
        $resolutores = Resolutor::where([
         ['rutEmpresa', '90413000-1'],
         ['estado', 1],
        ])->get();
        
        
        $a = intval(date("dH"));
        $b = intval(date("d")."22");
    
        if($a > $b)
        {
            $hoy = date("d-m-Y");
        } else
        {
            $hoy = date("d-m-Y", strtotime("-1 day"));
        }

        $ayer = date("d-m-Y", strtotime("-1 day"));
        
        $ayer_dtt = new DateTime($ayer);
        $hoy_dtt = new DateTime($hoy);
        
        $horas_modifica_ayer = new DateInterval('PT23H59M59S');
        $ayer_dtt = $ayer_dtt->add($horas_modifica_ayer);
        
        $hoy_dtt = $hoy_dtt->add($horas_modifica_ayer);
        
        $array_equipos = [];
        $array_resolutores = [];
        $array_resolutores_completo = [];
        $array_pendientes_resolutor = [];
        $array_creadoHoy_resolutor = [];
        $array_vencidos = [];
        $array_cerrados_hoy=[];
        $array_pendientes_resolutor_hoy= [];
        $total_activos_ayer = 0;
        $total_vencidos = 0;
        $total_creado_hoy = 0;
        $total_cerrados_hoy = 0;
        $total_activos_hoy = 0;
        
        
        foreach ($equipos as $equipo)
        {
            foreach($resolutores as $resolutor)
            {
                if($resolutor['idTeam'] == $equipo['id'])
                {
                    $array_equipos[] = $equipo['nameTeam'];
                    $array_resolutores_completo[] = $resolutor;
                }                
            }
        } 
        
        //Calcular pendientes al día anterior al actual
        foreach($array_resolutores_completo as $resolutor)
        {
            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado', '<', 100],
            ])->get();
            
            $contador = 0;
            foreach ($requerimientos_resolutor as $requerimiento)
            {
               $fecha_crea_req = new DateTime($requerimiento['created_at']);
               
               if($fecha_crea_req < $ayer_dtt)
               {
                   $contador = $contador+1;
                   ++$total_activos_ayer;
               }
            }
            
            $contador_2 = 0;
            foreach ($requerimientos_resolutor as $requerimiento)
            {
                $fecha_cierre_req = new DateTime($requerimiento['fechaCierre']);
                if($fecha_cierre_req<$hoy_dtt)
                {
                    ++$contador_2;
                    ++$total_vencidos;
                }
            }
            
            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado', null],
            ])->get();

            foreach ($requerimientos_resolutor as $requerimiento)
            {
               $fecha_crea_req = new DateTime($requerimiento['created_at']);
               
               if($fecha_crea_req < $ayer_dtt)
               {
                   $contador = $contador+1;
                   ++$total_activos_ayer;
               }
                $fecha_cierre_req = new DateTime($requerimiento['fechaCierre']);
                if($fecha_cierre_req<$hoy_dtt)
                {
                    ++$contador_2;
                    ++$total_vencidos;
                }               
            }
            
            $array_vencidos[] = $contador_2;
            
            $requerimientos_resolutor = Requerimiento::where([
               ['rutEmpresa', '90413000-1'],
               ['resolutor', $resolutor['id']],
               ['fechaLiquidacion', '!=', null] 
            ])->get();
            
            foreach ($requerimientos_resolutor as $requerimiento)
            {
                $fecha_liquida = new DateTime($requerimiento['fechaLiquidacion']);
                if($fecha_liquida>$ayer_dtt)
                {
                   $contador = $contador+1;
                   ++$total_activos_ayer;                    
                }
            }
            $array_pendientes_resolutor[] = $contador;
            //Acá termina
            
            //Calcular creados hoy
            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado','<',100]
            ])->get();            
          
            $contador = 0;
            foreach ($requerimientos_resolutor as $requerimiento)
            {
                $fecha_crea_req = new DateTime($requerimiento['created_at']);
                
                if($fecha_crea_req->getTimestamp() > $ayer_dtt->getTimestamp() and $fecha_crea_req->getTimestamp()<$hoy_dtt->getTimestamp()){
                    ++$contador;
                    ++$total_creado_hoy;
                }
            }

            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado', null]
            ])->get();            

            foreach ($requerimientos_resolutor as $requerimiento)
            {
                $fecha_crea_req = new DateTime($requerimiento['created_at']);
                
                if($fecha_crea_req->getTimestamp() > $ayer_dtt->getTimestamp() and $fecha_crea_req->getTimestamp()<$hoy_dtt->getTimestamp()){
                    ++$contador;
                    ++$total_creado_hoy;
                }
            }            
            $array_creadoHoy_resolutor[]=$contador;
            
            $array_resolutores[] = $resolutor['nombreResolutor'];
            
            
            //Calcular cerrados hoy
            
            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['fechaLiquidacion', '!=',""]
            ])->get();
            
            $cerrados = 0;
            foreach ($requerimientos_resolutor as $requerimiento)
            {
                $fl = new DateTime($requerimiento['fechaLiquidacion']);
                $fecha_liquidacion = $fl->format('d-m-Y');
                $al = new DateTime();
                $actual = $al->format('d-m-Y');
                if($actual == $fecha_liquidacion)
                {
                    ++$cerrados;
                    ++$total_cerrados_hoy;
                }   
            }
            
            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 2],
                ['fechaLiquidacion', '!=',""]
            ])->get();
            
            foreach ($requerimientos_resolutor as $requerimiento)
            {
                $fl = new DateTime($requerimiento['fechaLiquidacion']);
                $fecha_liquidacion = $fl->format('d-m-Y');
                $al = new DateTime();
                $actual = $al->format('d-m-Y');
                if($actual == $fecha_liquidacion)
                {
                    ++$cerrados;
                    ++$total_cerrados_hoy;
                }   
            }
            $array_cerrados_hoy[] = $cerrados;

            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado', '<', 100],
            ])->get();
            
            $contador = 0;
            foreach ($requerimientos_resolutor as $requerimiento)
            {
               $fecha_crea_req = new DateTime($requerimiento['created_at']);
               
               if($fecha_crea_req < $hoy_dtt)
               {
                   $contador = $contador+1;
                   ++$total_activos_hoy;
               }
            }

            $requerimientos_resolutor = Requerimiento::where([
                ['rutEmpresa', '90413000-1'],
                ['resolutor', $resolutor['id']],
                ['estado', 1],
                ['porcentajeEjecutado', null],
            ])->get();

            foreach ($requerimientos_resolutor as $requerimiento)
            {
               $fecha_crea_req = new DateTime($requerimiento['created_at']);
               
               if($fecha_crea_req < $hoy_dtt)
               {
                   $contador = $contador+1;
                   ++$total_activos_hoy;
               }
            }            
            $array_pendientes_resolutor_hoy[] = $contador;           
        }
        
        $puntero_1 = 0;
        
        for ($index = $puntero_1; $index < count($array_equipos); $index++) {
            for ($index1 = $puntero_1+1; $index1 < count($array_equipos); $index1++) {
                if ($index != $index1) {
                    if($array_equipos[$index] == $array_equipos[$index1])
                    {
                        $array_equipos[$index1] = "";
                    }
                    
                }
            }    
            ++$puntero_1;
        }

        $hoy = date("d-m-Y");
        $ayer = date("d-m-Y", strtotime("-1 day"));

        $valores['resolutores_array'] = $array_resolutores;
        $valores['equipos_array'] = $array_equipos;
        $valores['vencidos'] = $array_vencidos;
        $valores['creadoHoy_resolutor'] = $array_creadoHoy_resolutor;
        $valores['cerrados'] = $array_cerrados_hoy;
        $valores['pendientes_resolutor_hoy'] = $array_pendientes_resolutor_hoy;
        $valores['total_activos_ayer'] = $total_activos_ayer;
        $valores['total_vencidos'] = $total_vencidos;
        $valores['total_creados_hoy'] = $total_creado_hoy;
        $valores['total_cerrados_hoy'] = $total_cerrados_hoy;
        $valores['total_activos_hoy'] = $total_activos_hoy;
    
        Mail::to('dtapia1025@gmail.com')->send(new SendMailable($hoy, $ayer, $valores));
    }
}
