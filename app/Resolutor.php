<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resolutor extends Model
{
       /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['nombreResolutor', 'rutEmpresa', 'idTeam', 'idUser', 'email'];

    public static function resolutor($id){
    	return Resolutor::where('idTeam','=',$id)->get();
    }
}
