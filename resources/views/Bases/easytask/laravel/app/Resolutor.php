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
    protected $fillable = ['nombreResolutor', 'idEmpresa', 'idTeam'];
}
