<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use App\Empresa;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_user = Role::where('nombre', 'usuario')->first();
        $role_admin = Role::where('nombre', 'administrador')->first();
        $empresa = new Empresa();
        $empresa->nombreEmpresa = "CCU";
        $empresa->save();
        $user = new User();
        $user->name = 'User';
        $user->email = 'user@example.com';
        $user->idEmpresa = 1;
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_user);
        $user = new User();
        $user->name = 'Admin';
        $user->email = 'admin@example.com';
        $user->idEmpresa = 1;        
        $user->password = bcrypt('secret');
        $user->save();
        $user->roles()->attach($role_admin);
    }
}
