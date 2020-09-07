<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EliminaInfoReporte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elimina:reporte';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elminia la informacion almacenada en la tabla notas_resolutores';

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
        DB::table('notas_resolutores')->delete();
    }
}
