<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogRequerimientos extends Model
{
       /**
     * Fields that can be mass assigned.
     *
     * @var array
     */
    protected $fillable = ['idRequerimiento', 'idUsuario', 'campo', 'tipo'];
}
