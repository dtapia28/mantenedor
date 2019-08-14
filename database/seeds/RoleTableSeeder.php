<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->nombre = 'administrador';
        $role->descripcion = 'Administrador';
        $role->save();
        $role = new Role();
        $role->nombre = 'usuario';
        $role->descripcion = 'Usuario';
        $role->save();
        $role = new Role();
        $role->nombre = 'supervisor';
        $role->descripcion = 'Supervisor';
        $role->save();
        $role = new Role();
        $role->nombre = 'resolutor';
        $role->descripcion = 'Resolutor';
        $role->save();             
    }
}
