<?php

use Illuminate\Database\Seeder;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Bouncer::allow('usuario')->to('ver');
       Bouncer::allow('editor')->to('ver');
       Bouncer::allow('editor')->to('editar');
       Bouncer::allow('editor')->to('crear');
       Bouncer::allow('administrador')->to('ver');
       Bouncer::allow('administrador')->to('editar');
       Bouncer::allow('administrador')->to('crear');       
       Bouncer::allow('administrador')->to('eliminar'); 
    }
}
